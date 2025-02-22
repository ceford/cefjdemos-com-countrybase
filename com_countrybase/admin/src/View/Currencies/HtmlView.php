<?php

/**
 * @package     Countrybase.Administrator
 * @subpackage  com_countrybase
 *
 * @copyright   (C) 2025 Clifford E Ford
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Cefjdemos\Component\Countrybase\Administrator\View\Currencies;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for countrybase.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The search tools form
     *
     * @var    Form
     */
    public $filterForm;

    /**
     * The active search filters
     *
     * @var    array
     */
    public $activeFilters = [];

    /**
     * An array of items
     *
     * @var    array
     */
    protected $items = [];

    /**
     * The pagination object
     *
     * @var    Pagination
     */
    protected $pagination;

    /**
     * The model state
     *
     * @var    Registry
     */
    protected $state;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function display($tpl = null): void
    {

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');


        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     */
    protected function addToolbar(): void
    {
        $app = Factory::getApplication();

        ToolbarHelper::title(Text::_('COM_COUNTRYBASE_CURRENCIES'));

        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        $user = Factory::getApplication()->getIdentity();

        if ($user->authorise('core.admin', 'com_countrybase')) {
            $toolbar->addNew('currency.add');
        }

        if ($user->authorise('core.edit.state', 'com_countrybase')) {
            $dropdown = $toolbar->dropdownButton('status-group')
                ->text('JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childBar = $dropdown->getChildToolbar();

            $childBar->publish('currencies.publish')->listCheck(true);

            $childBar->unpublish('currencies.unpublish')->listCheck(true);

            $childBar->archive('currencies.archive')->listCheck(true);

            if ($this->state->get('filter.published') != -2) {
                $childBar->trash('currencies.trash')->listCheck(true);
            }
        }

        if ($this->state->get('filter.published') == -2 && $user->authorise('core.delete', 'com_countrybase')) {
            $toolbar->delete('currencies.delete')
                ->text('JTOOLBAR_EMPTY_TRASH')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if ($user->authorise('core.admin', 'com_countrybase') || $user->authorise('core.options', 'com_countrybase')) {
            $toolbar->preferences('com_countrybase');
        }

        $tmpl = $app->input->getCmd('tmpl');
        if ($tmpl !== 'component') {
            ToolbarHelper::help('countrybase', true);
        }
    }
}
