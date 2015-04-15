<?php namespace Greenalert\Http\Controllers;

use Illuminate\Pagination\BootstrapThreePresenter;

class FlatUIPresenter extends BootstrapThreePresenter {

    public function render()
    {
        if ($this->hasPages())
        {
            return sprintf(
                '<div class="pagination"><ul>%s %s %s</ul></div>',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            );
        }

        return '';
    }

}
