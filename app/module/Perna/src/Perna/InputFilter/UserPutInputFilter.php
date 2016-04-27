<?php
namespace Perna\InputFilter;


class UserPutInputFilter extends UserInputFilter {

    public function __construct () {
        parent::__construct();
        $this->get("password")->setRequired(false);
    }
}