<?php
/**
 * File containing after move workflow to re-index eZFind entries
 *
 * @author  Mark Simon <m.simon@all2e.com>
 * @copyright Copyright (C) 2011 all2e GmbH. All rights reserved.
 * @license GNU General Public License v2.0
 * @package extension indexaftermove
 * @version 1.0
 */


/*!
    \class indexaftermoveType indexaftermove.php
    \brief Workflow to re-index objects after content move
 */
class indexaftermoveType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'indexaftermove';
    const WORKFLOW_TYPE_CLASS = 'indexaftermoveType';
    const WORKFLOW_TYPE_DESC = 're-index node after being moved';

    /*!
     Initialize as content move after.
    */
    function indexaftermoveType()
    {
        $this->eZWorkflowEventType( self::WORKFLOW_TYPE_STRING, self::WORKFLOW_TYPE_DESC );
        $this->setTriggerTypes( array( 'content' =>  array(  'move' => array( 'after' ) ) ) );
    }

    /*!
     execution fetches the object and refreshes the eZ Find Index for that entry
    */
    function execute( $process, $event )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $objectID = $parameters['object_id'];
        $object = eZContentObject::fetch( $objectID );
        
        if( $object )
        {
            $searchEngine = new eZSolr();
            $searchEngine->addObject( $object, true );
        }

        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( indexaftermoveType::WORKFLOW_TYPE_STRING, indexaftermoveType::WORKFLOW_TYPE_CLASS );

?>
