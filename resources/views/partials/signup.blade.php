<article class="login-page" data-ng-controller="SignupController">
  <div class="login-box">
    <div class="login-logo">
              <a href=""><b>Sign</b>up</a>
    </div>
    <div class="login-box-body">

        <form method="post" ng-submit="signup()" name="signupForm">
          <div class="form-group" ng-class="{ 'has-error' : signupForm.displayName.$invalid && signupForm.displayName.$dirty }">
            <input class="form-control" type="text" name="displayName" ng-model="user.displayName" placeholder="Name" required autofocus>

            <div class="help-block text-danger" ng-if="signupForm.displayName.$dirty" ng-messages="signupForm.displayName.$error">
              <div ng-message="required">You must enter your name.</div>
            </div>
          </div>
          <div class="form-group" ng-class="{ 'has-error' : signupForm.email.$invalid && signupForm.email.$dirty }">
            <input class="form-control" type="email" id="email" name="email" ng-model="user.email" placeholder="Email" required>

            <div class="help-block text-danger" ng-if="signupForm.email.$dirty" ng-messages="signupForm.email.$error">
              <div ng-message="required">Your email address is required.</div>
              <div ng-message="pattern">Your email address is invalid.</div>
            </div>
          </div>
          <div class="form-group" ng-class="{ 'has-error' : signupForm.password.$invalid && signupForm.password.$dirty }">
            <input password-strength class="form-control" type="password" name="password" ng-model="user.password" placeholder="Password" required>

            <div class="help-block text-danger" ng-if="signupForm.password.$dirty" ng-messages="signupForm.password.$error">
              <div ng-message="required">Password is required.</div>
            </div>
          </div>
          <div class="form-group" ng-class="{ 'has-error' : signupForm.confirmPassword.$invalid && signupForm.confirmPassword.$dirty }">
            <input password-match="user.password" class="form-control" type="password" name="confirmPassword" ng-model="confirmPassword" placeholder="Confirm Password">
            <span class="ion-key form-control-feedback"></span>
            <div class="help-block text-danger" ng-if="signupForm.confirmPassword.$dirty" ng-messages="signupForm.confirmPassword.$error">
              <div ng-message="compareTo">Password must match.</div>
            </div>
          </div>
          <p class="text-center text-muted"><small>By clicking on Sign up, you agree to <a href="/terms">terms & conditions</a> and <a href="#">privacy policy</a></small></p>
          <button type="submit" ng-disabled="signupForm.$invalid" class="btn btn-block btn-primary">Sign up</button>
          <br/>
          <p class="text-center text-muted">Already have an account? <a href="/login">Log in now</a></p>
        </form>
      </div>
    </div>
</article>