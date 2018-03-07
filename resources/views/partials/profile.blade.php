<article class="profile-page" data-ng-controller="ProfileController">
    <div class="panel panel-default">
    <div class="panel-heading">Profile</div>
    <div class="panel-body">
      <legend><i class="ion-clipboard"></i> Edit My Profile</legend>
      <form method="post" ng-submit="updateProfile()">
        <div class="form-group">
          <label class="control-label">Profile Picture</label>
          <img class="profile-picture" ng-src="{{user.picture || 'https://placehold.it/100x100'}}">
        </div>
        <div class="form-group">
          <label class="control-label"><i class="ion-person"></i> Display Name</label>
          <input type="text" class="form-control" ng-model="user.displayName" />
        </div>
        <div class="form-group">
          <label class="control-label"><i class="ion-at"></i> Email Address</label>
          <input type="email" class="form-control" ng-model="user.email" />
        </div>
        <button class="btn btn-lg btn-success">Update Information</button>
      </form>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">Accounts</div>
    <div class="panel-body">
      <legend><i class="ion-link"></i> Linked Accounts</legend>
      <div class="btn-group-vertical">
        <button class="btn btn-sm btn-danger" ng-if="user.facebook" ng-click="unlink('facebook')">
          <i class="ion-social-facebook"></i> Unlink Facebook Account
        </button>
        <button class="btn btn-sm btn-primary" ng-if="!user.facebook" ng-click="link('facebook')">
          <i class="ion-social-facebook"></i> Link Facebook Account
        </button>

        <button class="btn btn-sm btn-danger" ng-if="user.google" ng-click="unlink('google')">
          <i class="ion-social-googleplus"></i> Unlink Google Account
        </button>
        <button class="btn btn-sm btn-primary" ng-if="!user.google" ng-click="link('google')">
          <i class="ion-social-googleplus"></i> Link Google Account
        </button>



        <button class="btn btn-sm btn-danger" ng-if="user.instagram" ng-click="unlink('instagram')">
          <i class="ion-social-instagram"></i> Unlink Instagram Account
        </button>
        <button class="btn btn-sm btn-primary" ng-if="!user.instagram" ng-click="link('instagram')">
          <i class="ion-social-instagram"></i> Link Instagram Account
        </button>



      </div>
    </div>
  </div>
</article>
