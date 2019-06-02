<?php

namespace atk4\ui\TableColumn;

use atk4\data\Field;

/**
 * Class Tooltip
 *
 * column to add a little icon to show on hover a text
 * text is taken by the Row Model in $tooltip_field
 *
 * @usage   : $crud->addDecorator('paid_date',  new \atk4\ui\TableColumn\Tooltip('note'));
 *
 * @usage   : $crud->addDecorator('paid_date',  new \atk4\ui\TableColumn\Tooltip('note','error red'));
 *
 * @package atk4\ui\TableColumn
 */
class Tooltip extends Generic
{
    /**
     *
     * @var string $field_tooltip
     */
    public $icon = [];

    /**
     *
     * @var string $tooltip_field
     */
    public $tooltip_field = [];

    /**
     * Pass argument of tooltip field
     *
     * @param string $field_tooltip
     * @param string $icon
     */
    public function __construct($field_tooltip = NULL, $icon = 'info circle blue')
    {
        $this->tooltip_field = $field_tooltip;
        $this->icon          = $icon;
    }

    public function getDataCellHTML(Field $f = NULL, $extra_tags = [])
    {
        if ($f === NULL) {
            throw new Exception(['Tooltip can be used only with model field']);
        }

        $attr = $this->getTagAttributes('body');

        $extra_tags = array_merge_recursive($attr, $extra_tags, ['class' => '{$_' . $f->short_name . '_tooltip}']);

        if (isset($extra_tags['class']) && is_array($extra_tags['class'])) {
            $extra_tags['class'] = implode(' ', $extra_tags['class']);
        }

        return $this->app->getTag('td', $extra_tags, [
                ' {$' . $f->short_name . '}' . $this->app->getTag('span', [
                        'class'        => 'ui icon link {$_' . $f->short_name . '_data_visible_class}',
                        'data-tooltip' => '{$_' . $f->short_name . '_data_tooltip}'
                    ], [
                        ['i', ['class' => 'ui icon {$_' . $f->short_name . '_icon}']]
                    ])
            ]);
    }

    public function getHTMLTags($row, $field)
    {
        // @TODO remove popup tooltip when null
        $tooltip = $row->data[$this->tooltip_field];

        if (is_null($tooltip) || empty($tooltip)) {

            return [
                '_' . $field->short_name . '_data_visible_class' => 'transition hidden',
                '_' . $field->short_name . '_data_tooltip'       => '',
                '_' . $field->short_name . '_icon'               => '',
            ];
        }

        return [
            '_' . $field->short_name . '_data_visible_class' => '',
            '_' . $field->short_name . '_data_tooltip'       => $tooltip,
            '_' . $field->short_name . '_icon'               => $this->icon,
        ];
    }
}
