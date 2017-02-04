<?php
/**
 * NewsDates.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <webmaster@pggo.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.2 (http://modulestudio.de) at Fri Feb 03 18:45:41 CET 2017.
 */

/**
 * Bootstrap called when application is first initialised at runtime.
 *
 * This is only called once, and only if the core has reason to initialise this module,
 * usually to dispatch a controller request or API.
 */
$container = ServiceUtil::get('service_container');


// check if own service exists (which is not true if the module is not installed yet)
$container = ServiceUtil::get('service_container');
if ($container->has('pggo_newsdates_module.archive_helper')) {
    $container->get('pggo_newsdates_module.archive_helper')->archiveObjects();
}

