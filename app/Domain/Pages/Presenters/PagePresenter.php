<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/5/2018
 * Time: 2:44 AM
 */

namespace Smartville\Domain\Pages\Presenters;

use Robbo\Presenter\Presenter;

class PagePresenter extends Presenter
{
    public function presentPrettyUri()
    {
        return '/' . ltrim($this->uri, '/');
    }
}