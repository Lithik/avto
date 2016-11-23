
<?= $this->getContent() ?>

<div class="page-header">
    <h2>Регистрация</h2>
</div>




<?= $this->tag->form(['register', 'id' => 'registerForm', 'onbeforesubmit' => 'return false']) ?>

    <fieldset>



        <div class="control-group">
            <?= $form->label('email', ['class' => 'control-label']) ?>
            <div class="controls">
                <?= $form->render('email', ['class' => 'form-control']) ?>
                <p class="help-block">(обязательно для заполнения)</p>
                <div class="alert alert-warning" id="email_alert">
                    <strong>Предупреждение!</strong> Введите Ваш email
                </div>
            </div>
        </div>

        <div class="control-group">
            <?= $form->label('password', ['class' => 'control-label']) ?>
            <div class="controls">
                <?= $form->render('password', ['class' => 'form-control']) ?>
                <p class="help-block">(минимум 8 символов)</p>
                <div class="alert alert-warning" id="password_alert">
                    <strong>Warning!</strong> Введите Ваш пароль
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="repeatPassword">Повторите пароль</label>
            <div class="controls">
                <?= $this->tag->passwordField(['repeatPassword', 'class' => 'input-xlarge']) ?>
                <div class="alert" id="repeatPassword_alert">
                    <strong>Warning!</strong> Пароли не совпадают
                </div>
            </div>
        </div>
        <br>
        <div class="form-actions">
            <?= $this->tag->submitButton(['Register', 'class' => 'btn btn-primary', 'onclick' => 'return SignUp.validate();']) ?>
            
        </div>

    </fieldset>
</form>
