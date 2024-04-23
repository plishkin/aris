<?php

class MyControllerExtension extends Extension {

    public function onAfterInit() {
        i18n::set_locale(Translatable::get_current_locale());
    }

}