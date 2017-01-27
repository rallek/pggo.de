<?php
/**
 * Team.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

/**
 * Delete operation.
 *
 * @param object $entity The treated object
 * @param array  $params Additional arguments
 *
 * @return bool False on failure or true if everything worked well
 *
 * @throws RuntimeException Thrown if executing the workflow action fails
 */
function PggoTeamModule_operation_delete(&$entity, $params)
{

    // get attributes read from the workflow
    if (isset($params['nextstate']) && !empty($params['nextstate'])) {
        // assign value to the data object
        $entity['workflowState'] = $params['nextstate'];
    }
    
    // get entity manager
    $container = \ServiceUtil::get('service_container');
    $entityManager = $container->get('doctrine.orm.default_entity_manager');
    $logger = $container->get('logger');
    $logArgs = ['app' => 'PggoTeamModule', 'user' => $container->get('zikula_users_module.current_user')->get('uname')];
    
    // delete entity
    try {
        $entityManager->remove($entity);
        $entityManager->flush();
        $result = true;
        $logger->notice('{app}: User {user} deleted an entity.', $logArgs);
    } catch (\Exception $e) {
        $logger->error('{app}: User {user} tried to delete an entity, but failed.', $logArgs);
        throw new \RuntimeException($e->getMessage());
    }

    // return result of this operation
    return $result;
}
