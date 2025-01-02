<?php

/**
 * @package     Ffmap.Administrator
 * @subpackage  com_ffmap
 *
 * @copyright   (C) 2021 Clifford E Ford
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cefjdemos\Component\Countrybase\Administrator\Table;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * Featured Table class.
 *
 * @since  1.6
 */
class CountriesTable extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since   1.6
     */
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__countrybase_countries', 'id', $db);

        $this->setColumnAlias('published', 'state');
    }
}
