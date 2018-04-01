<article class="admin-page">
    <h1>Reviews</h1>
    <table>
        <tr>
            <td>string_id</td>
            <td>status</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr data-ng-repeat="review in reviews">
            <td data-ng-bind="review.string_id"></td>
            <td data-ng-bind="review.status"></td>
            <td data-ng-bind="review.api_verb"></td>
            <td data-ng-bind="review.api_body"></td>
            <td><button class="btn" data-ng-click="accept(review)">Accept</button></td>
            <td><button class="btn" data-ng-click="reject(review)">Reject</button></td>
        </tr>
    </table>
</article>