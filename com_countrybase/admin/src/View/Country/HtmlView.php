<?php

/**
 * @package     Countrybase.Administrator
 * @subpackage  com_countrybase
 *
 * @copyright   (C) 2025 Clifford E Ford
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Cefjdemos\Component\Countrybase\Administrator\View\Country;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a country.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The \JForm object
     *
     * @var  \JForm
     */
    protected $form;

    /**
     * The active item
     *
     * @var  object
     */
    protected $item;

    /**
     * The model state
     *
     * @var  object
     */
    protected $state;

    /**
     * The actions the user is authorised to perform
     *
     * @var  \JObject
     */
    protected $canDo;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     *
     * @throws \Exception
     */
    public function display($tpl = null)
    {
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        return parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @throws \Exception
     */
    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);
        $isNew      = ($this->item->id == 0);

        $canDo = ContentHelper::getActions('com_countrybase');

        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(
            Text::_('COM_COUNTRYBASE_COUNTRY_PAGE_TITLE_' . ($isNew ? 'ADD' : 'EDIT'))
        );

        if ($canDo->get('core.create')) {
            if ($isNew) {
                $toolbar->apply('country.save');
            } else {
                $toolbar->apply('country.apply');
            }
            $toolbar->save('country.save');
        }
        $toolbar->cancel('country.cancel', 'JTOOLBAR_CLOSE');

        ToolbarHelper::inlinehelp();
    }
}
