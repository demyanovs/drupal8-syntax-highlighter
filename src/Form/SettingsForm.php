<?php

namespace Drupal\syntax_highlighter\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class SettingsForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'syntax_highlighter_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        // Return name config file.
        return [
            'syntax_highlighter.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('syntax_highlighter.settings');

        $form['settings'] = [
            '#type' => 'checkboxes',
            '#title' => 'Settings',
            '#description' => 'After changing the configuration, reset the cache',
            '#options' => [
                'show_line_numbers' => 'Show line numbers',
                'show_action_panel' => 'Show action panel'
            ],
            '#default_value' => $config->get('settings'),
        ];

        $types = \Drupal::entityTypeManager()
            ->getStorage('node_type')
            ->loadMultiple();

        $content_types = [];
        if ($types) {
            foreach ($types as $type) {
                $content_types[$type->id()] = $type->label();
            }
        }

        $form['enabled_content_types'] = [
            '#type' => 'checkboxes',
            '#title' => 'Enabled Content Types',
            '#description' => 'Use SyntaxHighlighter for all enabled content types only',
            '#options' => $content_types,
            '#default_value' => $config->get('enabled_content_types'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Retrieve the configuration
        $this->configFactory->getEditable('syntax_highlighter.settings')
            // Set the submitted configuration setting
            ->set('settings', $form_state->getValue('settings'))
            ->set('enabled_content_types', $form_state->getValue('enabled_content_types'))
            ->save();

        parent::submitForm($form, $form_state);
    }


}
