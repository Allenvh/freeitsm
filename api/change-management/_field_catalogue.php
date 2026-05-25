<?php
/**
 * Field catalogue for the Change form layout — the canonical list of
 * field keys the change editor supports, with their UI labels and
 * preferred render width. Required by api/change-management/
 * get_field_layout.php and save_field_layout.php so both endpoints
 * share one source of truth.
 *
 * `width` controls how the change form lays the field out:
 *   - 'half' → field can pair with an adjacent half-width field on
 *              the same row (dropdowns, datetime pickers, short text).
 *              An orphan half (single half between two fulls, or at
 *              the end of a section) stretches to full width so we
 *              don't leave awkward whitespace.
 *   - 'full' → field always takes the whole row (textareas, composite
 *              widgets like CAB or risk-scoring, headline fields like
 *              Title).
 * Width is a property of the field type, not its placement — moving a
 * dropdown to a different section keeps it half-width.
 *
 * To add a new field to the change form:
 *   1. Add the key here with its label + width
 *   2. Insert a row into change_field_layout pointing it at a section
 *      (or let the settings UI's "unplaced fields" surface it)
 *   3. Make sure the matching DOM template exists in
 *      change-management/index.php's editor form
 */
if (!defined('FIELD_CATALOGUE')) {
    define('FIELD_CATALOGUE', [
        'title'        => ['label' => 'Title',                       'width' => 'full'],
        'change_type'  => ['label' => 'Change type',                 'width' => 'half'],
        'status'       => ['label' => 'Status',                      'width' => 'half'],
        'priority'     => ['label' => 'Priority',                    'width' => 'half'],
        'impact'       => ['label' => 'Impact',                      'width' => 'half'],
        'category'     => ['label' => 'Category',                    'width' => 'half'],
        'requester'    => ['label' => 'Requester',                   'width' => 'half'],
        'assigned_to'  => ['label' => 'Assigned to',                 'width' => 'half'],
        'approver'     => ['label' => 'Approver',                    'width' => 'half'],
        'cab'          => ['label' => 'CAB',                         'width' => 'full'],
        'work_start'   => ['label' => 'Work start',                  'width' => 'half'],
        'work_end'     => ['label' => 'Work end',                    'width' => 'half'],
        'outage_start' => ['label' => 'Outage start',                'width' => 'half'],
        'outage_end'   => ['label' => 'Outage end',                  'width' => 'half'],
        'description'  => ['label' => 'Description',                 'width' => 'full'],
        'reason'       => ['label' => 'Reason for change',           'width' => 'full'],
        'risk'         => ['label' => 'Risk evaluation',             'width' => 'full'],
        'testplan'     => ['label' => 'Test plan',                   'width' => 'full'],
        'rollback'     => ['label' => 'Rollback plan',               'width' => 'full'],
        'pir'          => ['label' => 'Post-implementation review',  'width' => 'full'],
        'attachments'  => ['label' => 'Attachments',                 'width' => 'full'],
    ]);
}
