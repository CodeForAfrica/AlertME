<?php namespace Greenalert\Http\Controllers;

use Illuminate\Pagination\BootstrapPresenter;

class FlatUIPresenter extends BootstrapPresenter {

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
