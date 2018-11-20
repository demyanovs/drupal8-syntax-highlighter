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
        var_dump($config->get('welcome_message'));

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
            ->save();

        parent::submitForm($form, $form_state);
    }


}
