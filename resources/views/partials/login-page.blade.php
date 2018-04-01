<article class="login-page" data-ng-controller="LoginController">  
       <div class="footer-banner">
                    <h1>showyBulgaria.com</h1>
                    <h2>Find beautiful hikes</h2>
        </div>
        <div class="login-box">
            <div class="login-logo">
              <a href=""><b>Log</b>in</a>
            </div>
            <div class="login-box-body">
                <form method="post" ng-submit="login()" name="loginForm">
                        <div class="form-group">
                            <input class="form-control" type="text" id="email" name="email" ng-model="user.email" placeholder="Email" required autofocus>   
                        </div>
                       
                        <div class="form-group">
                            <input class="form-control" id="password" type="password" name="password" ng-model="user.password" placeholder="Password" required>
                        </div>
                      
                        <button type="submit" ng-disabled="loginForm.$invalid" class="btn btn-block btn-success btn-login">Log in</button>
                    
                        <p class="text-center text-muted">
                            <small>Don't have an account yet? <a href="/signup">Sign up</a></small>
                        </p>
                        <div class="signup-or-separator">
                            <h6 class="text">or</h6>
                            <hr>
                        </div>

                        <button class="btn btn-block btn-social btn-facebook" ng-click="authenticate('facebook')">
                            <i class="fa fa-facebook"></i> Sign in with Facebook
                        </button>
                        <button class="btn btn-block btn-social btn-google btn-flat" ng-click="authenticate('google')">
                            <i class="fa fa-google-plus"></i> Sign in with Google
                        </button>

                        <button class="btn btn-block btn-social btn-instagram" ng-click="authenticate('instagram')">
                            <i class="fa fa-instagram"></i> Sign in with Instagram
                        </button>
                    
                </form>
            </div>    
        </div>
</article>
