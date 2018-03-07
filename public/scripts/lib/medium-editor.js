/*global console*/

function MediumEditor(elements, options) {
    'use strict';
    return this.init(elements, options);
}

(function (window, document) {
    'use strict';

    function extend(b, a) {
        var prop;
        if (b === undefined) {
            return a;
        }
        for (prop in a) {
            if (a.hasOwnProperty(prop) && b.hasOwnProperty(prop) === false) {
                b[prop] = a[prop];
            }
        }
        return b;
    }

    // http://stackoverflow.com/questions/5605401/insert-link-in-contenteditable-element
    // by Tim Down
    function saveSelection() {
        var i,
            len,
            ranges,
            sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            ranges = [];
            for (i = 0, len = sel.rangeCount; i < len; i += 1) {
                ranges.push(sel.getRangeAt(i));
            }
            return ranges;
        }
        return null;
    }

    function restoreSelection(savedSel) {
        var i,
            len,
            sel = window.getSelection();
        if (savedSel) {
            sel.removeAllRanges();
            for (i = 0, len = savedSel.length; i < len; i += 1) {
                sel.addRange(savedSel[i]);
            }
        }
    }

    // http://stackoverflow.com/questions/1197401/how-can-i-get-the-element-the-caret-is-in-with-javascript-when-using-contentedi
    // by You
    function getSelectionStart() {
        var node = document.getSelection().anchorNode,
            startNode = (node && node.nodeType === 3 ? node.parentNode : node);
        return startNode;
    }

    MediumEditor.prototype = {
        defaults: {
            anchorInputPlaceholder: 'Paste or type a link',
            delay: 0,
            diffLeft: 0,
            diffTop: -10,
            disableReturn: false,
            disableToolbar: false,
            excludedActions: [],
            firstHeader: 'h3',
            forcePlainText: true,
            placeholder: 'Type your text',
            secondHeader: 'h4'
        },

        init: function (elements, options) {
            this.elements = typeof elements === 'string' ? document.querySelectorAll(elements) : elements;
            if (this.elements.length === 0) {
                return;
            }
            this.isActive = true;
            this.parentElements = ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote'];
            this.id = document.querySelectorAll('.medium-editor-toolbar').length + 1;
            this.options = extend(options, this.defaults);
            return this.initElements()
                       .bindPaste()
                       .setPlaceholders()
                       .bindWindowActions();
        },

        initElements: function () {
            var i;
            for (i = 0; i < this.elements.length; i += 1) {
                this.elements[i].setAttribute('contentEditable', true);
                if (!this.elements[i].getAttribute('data-placeholder')) {
                    this.elements[i].setAttribute('data-placeholder', this.options.placeholder);
                }
                this.elements[i].setAttribute('data-medium-element', true);
                this.bindParagraphCreation(i).bindReturn(i);
                if (!this.options.disableToolbar && !this.elements[i].getAttribute('data-disable-toolbar')) {
                    this.initToolbar()
                        .bindSelect()
                        .bindButtons()
                        .bindAnchorForm();
                }
            }
            return this;
        },

        bindParagraphCreation: function (index) {
            var self = this;
            this.elements[index].addEventListener('keyup', function (e) {
                var node = getSelectionStart();
                if (node && node.getAttribute('data-medium-element') && node.children.length === 0) {
                    //document.execCommand('formatBlock', false, 'p');
                    // edit, no need because we're already in a keyup handler
                    //this.triggerKeyUp();
                }
                if (e.which === 13 && !e.shiftKey) {
                    if (!(self.options.disableReturn || this.getAttribute('data-disable-return'))) {
                        document.execCommand('formatBlock', false, 'p');
                        node = getSelectionStart();
                        if (node.tagName.toLowerCase() === 'a') {
                            document.execCommand('unlink', null, false);
                        }
                        // edit, no need because we're already in a keyup handler
                        //this.triggerKeyUp();
                    }
                }
            });
            return this;
        },

        bindReturn: function (index) {
            var self = this;
            this.elements[index].addEventListener('keypress', function (e) {
                if (e.which === 13 && !e.shiftKey) {
                    if (self.options.disableReturn || this.getAttribute('data-disable-return')) {
                        e.preventDefault();
                    }
                }
            });
        },

        //TODO: actionTemplate
        toolbarTemplate: function () {
            return '<ul id="medium-editor-toolbar-actions" class="medium-editor-toolbar-actions clearfix">' +
                '    <li><button class="medium-editor-action medium-editor-action-bold" data-action="bold" data-element="b">B</button></li>' +
                '    <li><button class="medium-editor-action medium-editor-action-italic" data-action="italic" data-element="i">I</button></li>' +
                '    <li><button class="medium-editor-action medium-editor-action-underline" data-action="underline" data-element="u">S</button></li>' +
                '    <li><button class="medium-editor-action medium-editor-action-anchor" data-action="anchor" data-element="a">#</button></li>' +
                '    <li><button class="medium-editor-action medium-editor-action-header1" data-action="append-' + this.options.firstHeader + '" data-element="' + this.options.firstHeader + '">h1</button></li>' +
                '    <li><button class="medium-editor-action medium-editor-action-header2" data-action="append-' + this.options.secondHeader + '" data-element="' + this.options.secondHeader + '">h2</button></li>' +
                '    <li><button class="medium-editor-action medium-editor-action-quote" data-action="append-blockquote" data-element="blockquote">&ldquo;</button></li>' +
                '</ul>' +
                '<div class="medium-editor-toolbar-form-anchor" id="medium-editor-toolbar-form-anchor">' +
                '    <input class="hide-ie-input-close" type="text" value="" placeholder="' + this.options.anchorInputPlaceholder + '"><a href="#">&times;</a>' +
                '</div>';
        },

        initToolbar: function () {
            this.toolbar = this.createToolbar();
            this.keepToolbarAlive = false;
            this.anchorForm = this.toolbar.querySelector('.medium-editor-toolbar-form-anchor');
            this.toolbarActions = this.toolbar.querySelector('.medium-editor-toolbar-actions');
            return this;
        },

        createToolbar: function () {
            var toolbar = document.createElement('div');
            toolbar.id = 'medium-editor-toolbar-' + this.id;
            toolbar.className = 'medium-editor-toolbar';
            toolbar.innerHTML = this.toolbarTemplate();
            document.getElementsByTagName('body')[0].appendChild(toolbar);
            return toolbar;
        },

        bindSelect: function () {
            var self = this,
                timer = '',
                i;

            this.checkSelectionWrapper = function (e) {
                clearTimeout(timer);
                setTimeout(function () {
                    self.checkSelection(e);
                }, self.options.delay);
            };
            window.addEventListener('mouseup', this.checkSelectionWrapper);
            
            for (i = 0; i < this.elements.length; i += 1) {
                //edits this.elements[i].addEventListener('mouseup', this.checkSelectionWrapper);
                this.elements[i].addEventListener('keyup', this.checkSelectionWrapper);
            }
            return this;
        },

        // edits
        isChildOfElements: function(element) {
            var parent = element;
            var isChild = false;
            while (parent !== null) {
                for (var i = 0; i < this.elements.length; i += 1) {
                    if (this.elements[i] === parent) {
                        isChild = true;
                        break;
                    }
                }
                parent = parent.parentNode;
            }
            return isChild;
        },

        checkSelection: function () {
            var newSelection;
            if (this.keepToolbarAlive !== true) {
                newSelection = window.getSelection();
                if (newSelection.toString().trim() === '') {
                    this.hideToolbar();
                } else if (this.isChildOfElements(newSelection.focusNode)) {
                    this.selection = newSelection;
                    this.selectionRange = this.selection.getRangeAt(0);
                    if (!this.getSelectionElement().getAttribute('data-disable-toolbar')) {
                        this.toolbar.style.display = 'block';
                        this.setToolbarButtonStates()
                            .setToolbarPosition()
                            .showToolbarActions();
                    }
                }
            }
            return this;
        },

        getSelectionElement: function () {
            var selection = window.getSelection(),
                range = selection.getRangeAt(0),
                parent = range.commonAncestorContainer.parentNode;
            try {
                while (!parent.getAttribute('data-medium-element')) {
                    parent = parent.parentNode;
                }
            } catch (err) {
                return this.elements[0];
            }
            return parent;
        },

        setToolbarPosition: function () {
            var buttonHeight = 50,
                selection = window.getSelection();
            if (!selection.rangeCount) {
                return;
            }
            var range = selection.getRangeAt(0),
                boundary = range.getBoundingClientRect(),
                defaultLeft = (this.options.diffLeft) - (this.toolbar.offsetWidth / 2),
                middleBoundary = (boundary.left + boundary.right) / 2,
                halfOffsetWidth = this.toolbar.offsetWidth / 2;
            if (boundary.top < buttonHeight) {
                // edit
                $(this.toolbar).addClass('medium-toolbar-arrow-over');
                $(this.toolbar).removeClass('medium-toolbar-arrow-under');
                this.toolbar.style.top = buttonHeight + boundary.bottom - this.options.diffTop + window.pageYOffset - this.toolbar.offsetHeight + 'px';
            } else {
                // edit
                $(this.toolbar).addClass('medium-toolbar-arrow-under');
                $(this.toolbar).removeClass('medium-toolbar-arrow-over');
                this.toolbar.style.top = boundary.top + this.options.diffTop + window.pageYOffset - this.toolbar.offsetHeight + 'px';
            }
            if (middleBoundary < halfOffsetWidth) {
                this.toolbar.style.left = defaultLeft + halfOffsetWidth + 'px';
            } else if ((window.innerWidth - middleBoundary) < halfOffsetWidth) {
                this.toolbar.style.left = window.innerWidth + defaultLeft - halfOffsetWidth + 'px';
            } else {
                this.toolbar.style.left = defaultLeft + middleBoundary + 'px';
            }
            return this;
        },

        setToolbarButtonStates: function () {
            var buttons = this.toolbarActions.querySelectorAll('button'),
                i;
            for (i = 0; i < buttons.length; i += 1) {
                // edit
                $(buttons[i]).removeClass('medium-editor-button-active');
                this.showHideButton(buttons[i]);
            }
            this.checkActiveButtons();
            return this;
        },

        showHideButton: function (button) {
            if (this.options.excludedActions.indexOf(button.getAttribute('data-element')) > -1) {
                button.style.display = 'none';
            } else {
                button.style.display = 'block';
            }
        },

        checkActiveButtons: function () {
            var parentNode = this.selection.anchorNode;
            if (!parentNode.tagName) {
                parentNode = this.selection.anchorNode.parentNode;
            }
            while (parentNode.tagName !== undefined && this.parentElements.indexOf(parentNode.tagName) === -1) {
                this.activateButton(parentNode.tagName.toLowerCase());
                parentNode = parentNode.parentNode;
            }
        },

        activateButton: function (tag) {
            var el = this.toolbar.querySelector('[data-element="' + tag + '"]');
            if (el !== null && el.className.indexOf('medium-editor-button-active') === -1) {
                el.className += ' medium-editor-button-active';
            }
        },

        bindButtons: function () {
            var buttons = this.toolbar.querySelectorAll('button'),
                i,
                self = this,
                triggerAction = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (self.selection === undefined) {
                        self.checkSelection(e);
                    }
                    if (this.className.indexOf('medium-editor-button-active') > -1) {
                        // edit
                        $(this).removeClass('medium-editor-button-active');
                    } else {
                        this.className += ' medium-editor-button-active';
                    }
                    self.execAction(this.getAttribute('data-action'), e);
                };
            for (i = 0; i < buttons.length; i += 1) {
                buttons[i].addEventListener('click', triggerAction);
            }
            this.setFirstAndLastItems(buttons);
            return this;
        },

        setFirstAndLastItems: function (buttons) {
            buttons[0].className += ' medium-editor-button-first';
            buttons[4].className += ' medium-editor-button-last'; // edit: this is the expedient fix
            return this;
        },

        execAction: function (action, e) {
            if (action.indexOf('append-') > -1) {
                this.execFormatBlock(action.replace('append-', ''));
                this.setToolbarPosition();
                this.setToolbarButtonStates();
            } else if (action === 'anchor') {
                this.triggerAnchorAction(e);
            } else {
                document.execCommand(action, null, false);
                this.setToolbarPosition();
                this.triggerKeyUp();
                // edit, http://stackoverflow.com/questions/7463908/how-to-change-output-html-tag-b-b-to-strong-strong
                // replace the strong / em elements with b / i respectively. This allows us to maintain the selection.
                var strongElements = $(this.elements).find('strong');
                for (var i = 0; i < strongElements.length; i++) {
                    var newElem = document.createElement('b');
                    newElem.innerHTML = strongElements[i].innerHTML;
                    strongElements[i].parentNode.replaceChild(newElem, strongElements[i]);
                }
                var emElements = $(this.elements).find('em');
                for (var i = 0; i < emElements.length; i++) {
                    var newElem = document.createElement('i');
                    newElem.innerHTML = emElements[i].innerHTML;
                    emElements[i].parentNode.replaceChild(newElem, emElements[i]);
                }
            }
        },

        triggerAnchorAction: function () {
            // edit
            var isAnchor = function(element) {
                return element && element.tagName && element.tagName.toLowerCase() === 'a';
            };

            var anchor = null;
            if (isAnchor(this.selection.anchorNode.parentNode)) {
                anchor = this.selection.anchorNode.parentNode;
            } else if (isAnchor(this.selection.focusNode.parentNode)) {
                anchor = this.selection.focusNode.parentNode;
            } else if ( this.selection.getRangeAt(0).commonAncestorContainer.childNodes &&
                        isAnchor(this.selection.getRangeAt(0).commonAncestorContainer.childNodes[1])) {
                anchor = this.selection.getRangeAt(0).commonAncestorContainer.childNodes[1];
            } else if ( this.selection.getRangeAt(0).commonAncestorContainer.children &&
                        isAnchor(this.selection.getRangeAt(0).commonAncestorContainer.children[1])) {
                anchor = this.selection.getRangeAt(0).commonAncestorContainer.children[1];
            }
            if (anchor) {
                anchor.href = "#"
                document.execCommand('unlink', false, false);
                this.triggerKeyUp();
            } else {
                if (this.anchorForm.style.display === 'block') {
                    this.showToolbarActions();
                } else {
                    this.showAnchorForm();
                }
            }
            return this;
        },

        execFormatBlock: function (el) {
            var selectionData = this.getSelectionData(this.selection.anchorNode);
            // FF handles blockquote differently on formatBlock
            // allowing nesting, we need to use outdent
            // https://developer.mozilla.org/en-US/docs/Rich-Text_Editing_in_Mozilla
            if (el === 'blockquote' && selectionData.el.parentNode.tagName.toLowerCase() === 'blockquote') {
                // edit
                this.triggerKeyUp();
                return document.execCommand('outdent', false, null);
            }
            if (selectionData.tagName === el) {
                el = 'p';
            }
            // edit, added < >
            this.triggerKeyUp();
            return document.execCommand('formatBlock', false, "<" + el + ">");
        },

        getSelectionData: function (el) {
            var tagName;

            if (el && el.tagName) {
                tagName = el.tagName.toLowerCase();
            }

            while (el && this.parentElements.indexOf(tagName) === -1) {
                el = el.parentNode;
                if (el && el.tagName) {
                    tagName = el.tagName.toLowerCase();
                }
            }

            return {
                el: el,
                tagName: tagName
            };
        },

        getFirstChild: function (el) {
            var firstChild = el.firstChild;
            while (firstChild !== null && firstChild.nodeType !== 1) {
                firstChild = firstChild.nextSibling;
            }
            return firstChild;
        },

        bindElementToolbarEvents: function (el) {
            var self = this;
            el.addEventListener('mouseup', function (e) {
                self.checkSelection(e);
            });
            el.addEventListener('keyup', function (e) {
                self.checkSelection(e);
            });
        },

        showToolbarActions: function () {
            var self = this,
                timeoutWrapper = function () {
                    self.keepToolbarAlive = false;
                    self.toolbar.style.display = 'none';
                    document.removeEventListener('click', timeoutWrapper);
                },
                timer;
            this.anchorForm.style.display = 'none';
            this.toolbarActions.style.display = 'block';
            if (this.previousLeft) {
                this.toolbar.style.left = this.previousLeft;
                this.previousLeft = null;
            }
            this.keepToolbarAlive = false;
            clearTimeout(timer);
            timer = setTimeout(function () {
                document.addEventListener('click', timeoutWrapper);
            }, 300);
        },

        showAnchorForm: function () {
            var input = this.anchorForm.querySelector('input');
            this.toolbarActions.style.display = 'none';
            this.savedSelection = saveSelection();
            this.anchorForm.style.display = 'block';
            this.keepToolbarAlive = true;
            // edit, if toolbar is too far to the right, shift it.
            if (parseInt((this.toolbar.style.left)) + parseInt((this.toolbar.offsetWidth)) >= $(window).width()) {
                //console.log(($(window).width() - parseInt((this.toolbar.offsetWidth)) - 20) + "px")
                this.previousLeft = this.toolbar.style.left;
                this.toolbar.style.left = ($(window).width() - parseInt((this.toolbar.offsetWidth)) - 50) + "px";
            }

            input.focus();
            input.value = '';
        },

        bindAnchorForm: function () {
            var input = this.anchorForm.querySelector('input'),
                linkCancel = this.anchorForm.querySelector('a'),
                self = this;
            this.anchorForm.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // edit
            if (navigator.userAgent.indexOf("MSIE") > -1) {
                input.addEventListener('keypress', function (e) {
                   if (e.keyCode === 13) {
                        self.createLink(this);
                         e.preventDefault();
                    }
                });  
            }

            input.addEventListener('keyup', function (e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    self.createLink(this);
                }
            });
            linkCancel.addEventListener('click', function (e) {
                e.preventDefault();
                self.showToolbarActions();
                restoreSelection(self.savedSelection);
            });
            return this;
        },

        createLink: function (input) {
            restoreSelection(this.savedSelection);
            // edit
            this.triggerKeyUp();
            document.execCommand('createLink', false, input.value);
            // begin edit
            var link = $(this.elements).find("a[href='" + input.value + "']");
            link.click(function() {
                return false;
            })
            // end edit
            this.showToolbarActions();
            input.value = '';
        },

        bindWindowActions: function () {
            var timerResize,
                self = this;
            window.addEventListener('resize', function () {
                clearTimeout(timerResize);
                timerResize = setTimeout(function () {
                    self.setToolbarPosition();
                }, 100);
            });
            return this;
        },

        hideToolbar: function() {
            this.toolbar.style.display = 'none';
        },

        activate: function () {
            var i;
            if (this.isActive) {
                return;
            }
            this.isActive = true;
            for (i = 0; i < this.elements.length; i += 1) {
                this.elements[i].setAttribute('contentEditable', true);
            }
            this.bindSelect();
        },

        deactivate: function () {
            var i;
            if (!this.isActive) {
                return;
            }
            this.isActive = false;
            this.hideToolbar();
            for (i = 0; i < this.elements.length; i += 1) {
                this.elements[i].removeEventListener('mouseup', this.checkSelectionWrapper);
                this.elements[i].removeEventListener('keyup', this.checkSelectionWrapper);
                this.elements[i].removeAttribute('contentEditable');
            }
        },

        bindPaste: function () {
            if (!this.options.forcePlainText) {
                return;
            }
            var i,
                pasteWrapper = function (e) {
                    if (navigator.userAgent.indexOf("MSIE") > -1) {
                        // edit
                        $(e.target).removeClass('medium-editor-placeholder-ie');
                    } else {
                        // edit
                        $(e.target).removeClass('medium-editor-placeholder');
                    }
                    // begin edit
                    //if (e.clipboardData && e.clipboardData.getData) {
                        //e.preventDefault();
                        //document.execCommand('insertHTML', false, e.clipboardData.getData('text/plain').replace(/[\r\n]/g, '<br>'));
                    //}
                };
            for (i = 0; i < this.elements.length; i += 1) {
                this.elements[i].addEventListener('paste', pasteWrapper);
            }
            return this;
        },

        setPlaceholders: function () {
            var i,
                activatePlaceholder = function (el) {
                    if (el.textContent.replace(/^\s+|\s+$/g, '') === '') {
                        // edit, see css for explanation
                        if (navigator.userAgent.indexOf("MSIE") > -1) {
                            // edit
                            $(el).addClass('medium-editor-placeholder-ie');
                        } else {
                            // edit
                            $(el).addClass('medium-editor-placeholder');
                        }
                    }
                },
                placeholderWrapper = function (e) {
                    // edit
                    if (navigator.userAgent.indexOf("MSIE") > -1) {
                        // edit
                        $(this).removeClass('medium-editor-placeholder-ie');
                    } else {
                        // edit
                        $(this).removeClass('medium-editor-placeholder');
                    }
                   
                    if (e.type !== 'keypress') {
                        activatePlaceholder(this);
                    }
                };
            for (i = 0; i < this.elements.length; i += 1) {
                activatePlaceholder(this.elements[i]);
                this.elements[i].addEventListener('blur', placeholderWrapper);
                this.elements[i].addEventListener('keypress', placeholderWrapper);
            }
            return this;
        },

        triggerKeyUp: function() {
            // edit, in IE execCommand doesn't trigger any events so we have to 
            // manually trigger one that will register the change
            var self = this;
            setTimeout(function() {
                for (var i = 0; i < self.elements.length; i++) {
                    $(self.elements[i]).keyup();
                    self.checkSelection();
                }
            });   
        }

    };

}(window, document));
