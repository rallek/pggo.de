<?php
/**
 * Instititutionen.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace Pggo\InstititutionenModule\Base;

/**
 * Events definition base class.
 */
abstract class AbstractInstititutionenEvents
{
    /**
     * The pggoinstititutionenmodule.image_post_load event is thrown when images
     * are loaded from the database.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postLoad()
     * @var string
     */
    const IMAGE_POST_LOAD = 'pggoinstititutionenmodule.image_post_load';
    
    /**
     * The pggoinstititutionenmodule.image_pre_persist event is thrown before a new image
     * is created in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::prePersist()
     * @var string
     */
    const IMAGE_PRE_PERSIST = 'pggoinstititutionenmodule.image_pre_persist';
    
    /**
     * The pggoinstititutionenmodule.image_post_persist event is thrown after a new image
     * has been created in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postPersist()
     * @var string
     */
    const IMAGE_POST_PERSIST = 'pggoinstititutionenmodule.image_post_persist';
    
    /**
     * The pggoinstititutionenmodule.image_pre_remove event is thrown before an existing image
     * is removed from the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::preRemove()
     * @var string
     */
    const IMAGE_PRE_REMOVE = 'pggoinstititutionenmodule.image_pre_remove';
    
    /**
     * The pggoinstititutionenmodule.image_post_remove event is thrown after an existing image
     * has been removed from the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postRemove()
     * @var string
     */
    const IMAGE_POST_REMOVE = 'pggoinstititutionenmodule.image_post_remove';
    
    /**
     * The pggoinstititutionenmodule.image_pre_update event is thrown before an existing image
     * is updated in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::preUpdate()
     * @var string
     */
    const IMAGE_PRE_UPDATE = 'pggoinstititutionenmodule.image_pre_update';
    
    /**
     * The pggoinstititutionenmodule.image_post_update event is thrown after an existing new image
     * has been updated in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterImageEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postUpdate()
     * @var string
     */
    const IMAGE_POST_UPDATE = 'pggoinstititutionenmodule.image_post_update';
    
    /**
     * The pggoinstititutionenmodule.institution_post_load event is thrown when institutions
     * are loaded from the database.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postLoad()
     * @var string
     */
    const INSTITUTION_POST_LOAD = 'pggoinstititutionenmodule.institution_post_load';
    
    /**
     * The pggoinstititutionenmodule.institution_pre_persist event is thrown before a new institution
     * is created in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::prePersist()
     * @var string
     */
    const INSTITUTION_PRE_PERSIST = 'pggoinstititutionenmodule.institution_pre_persist';
    
    /**
     * The pggoinstititutionenmodule.institution_post_persist event is thrown after a new institution
     * has been created in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postPersist()
     * @var string
     */
    const INSTITUTION_POST_PERSIST = 'pggoinstititutionenmodule.institution_post_persist';
    
    /**
     * The pggoinstititutionenmodule.institution_pre_remove event is thrown before an existing institution
     * is removed from the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::preRemove()
     * @var string
     */
    const INSTITUTION_PRE_REMOVE = 'pggoinstititutionenmodule.institution_pre_remove';
    
    /**
     * The pggoinstititutionenmodule.institution_post_remove event is thrown after an existing institution
     * has been removed from the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postRemove()
     * @var string
     */
    const INSTITUTION_POST_REMOVE = 'pggoinstititutionenmodule.institution_post_remove';
    
    /**
     * The pggoinstititutionenmodule.institution_pre_update event is thrown before an existing institution
     * is updated in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::preUpdate()
     * @var string
     */
    const INSTITUTION_PRE_UPDATE = 'pggoinstititutionenmodule.institution_pre_update';
    
    /**
     * The pggoinstititutionenmodule.institution_post_update event is thrown after an existing new institution
     * has been updated in the system.
     *
     * The event listener receives an
     * Pggo\InstititutionenModule\Event\FilterInstitutionEvent instance.
     *
     * @see Pggo\InstititutionenModule\Listener\EntityLifecycleListener::postUpdate()
     * @var string
     */
    const INSTITUTION_POST_UPDATE = 'pggoinstititutionenmodule.institution_post_update';
    
}