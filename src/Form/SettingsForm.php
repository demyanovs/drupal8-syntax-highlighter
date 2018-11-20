<?php
namespace Drupal\code_highlighter\Form;
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
        return 'code_highlighter_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        // Return name config file.
        return [
            'code_highlighter.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('code_highlighter.settings');

        $types = \Drupal::entityTypeManager()
            ->getStorage('node_type')
            ->loadMultiple();

        $content_types = [];
        foreach($types as $type) {
            $content_types[$type->id()] = $type->label();
        }

        $form['enabled_content_types'] = [
            '#type' => 'checkboxes',
            '#title' => 'Enabled Content Types',
            '#description' => 'Use CodeHighlighter for all enabled content types only',
            '#options' => $content_types,
            '#default_value' => $config->get('enabled_content_types'),
        ];

        $form['welcome_message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Welcome message'),
            '#description' => $this->t('Welcome message display to users when they login'),
            '#default_value' => $config->get('welcome_message'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Retrieve the configuration
        $this->configFactory->getEditable('code_highlighter.settings')
            // Set the submitted configuration setting
            ->set('welcome_message', $form_state->getValue('welcome_message'))
            ->set('enabled_content_types', $form_state->getValue('enabled_content_types'))
            ->save();

        var_dump($form_state->getValue('enabled_content_types'));
        //exit();

        parent::submitForm($form, $form_state);
    }


}
