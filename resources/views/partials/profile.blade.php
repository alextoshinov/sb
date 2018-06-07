<article class="profile-page" data-ng-controller="ProfileController">
    <div class="register-box">
        <div class="login-logo">
          <a href=""><b>P</b>rofile</a>
        </div>
        <div class="register-box-body">

        <form method="post" ng-submit="updateProfile()">
          <div class="form-group">
            <img class="profile-picture" ng-src="{{user.picture || 'images/d-user.jpg'}}">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Name" ng-model="user.displayName" />
          </div>
          <div class="form-group">
            <input type="email" class="form-control" placeholder="Email" ng-model="user.email" />
          </div>
          <button class="btn btn-block btn-success">Update Information</button>
        </form>
        
        <div class="signup-or-separator">
            <h6 class="text">Linked Accounts</h6>
            <hr>
        </div>

          <button class="btn btn-block btn-social btn-facebook" ng-if="user.facebook" ng-click="unlink('facebook')">
            <i class="fa fa-facebook"></i> Unlink Facebook Account
          </button>
          <button class="btn btn-block btn-social btn-facebook" ng-if="!user.facebook" ng-click="link('facebook')">
            <i class="fa fa-facebook"></i> Link Facebook Account
          </button>

          <button class="btn btn-block btn-social btn-google btn-flat" ng-if="user.google" ng-click="unlink('google')">
            <i class="fa fa-google-plus"></i> Unlink Google Account
          </button>
          <button class="btn btn-block btn-social btn-google btn-flat" ng-if="!user.google" ng-click="link('google')">
            <i class="fa fa-google-plus"></i> Link Google Account
          </button>



          <button class="btn btn-block btn-social btn-instagram" ng-if="user.instagram" ng-click="unlink('instagram')">
            <i class="fa fa-instagram"></i> Unlink Instagram Account
          </button>
          <button class="btn btn-block btn-social btn-instagram" ng-if="!user.instagram" ng-click="link('instagram')">
            <i class="fa fa-instagram"></i> Link Instagram Account
          </button>

    </div>
  </div>
</article>
