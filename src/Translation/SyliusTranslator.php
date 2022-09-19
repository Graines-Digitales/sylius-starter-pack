<?php

namespace App\Translation;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SyliusTranslator
{
    private $container;

    private $translator;

    public function __construct(
        ContainerInterface $container
        , Translator $translator
    ){
        $this->container = $container;
        $this->translator = $translator;
    }

    public function translateEntity($currentData, $referenceData, $form)
    {
        foreach($referenceData as $field=>$value) {
            /**
             * SQUIZZ NON TRANSLATABLE FIELD
             */
            $isTranslatable = $this->checkIfFieldIsTranslatable($field, $form);
            if(false === $isTranslatable
                || in_array($field, array_keys($form->all()))
            ) {
                continue;
            }
            
            $currentData[$field] = trim($currentData[$field]);
            $referenceData[$field] = trim($referenceData[$field]);
            if(empty($currentData[$field])
                && !empty($referenceData[$field])
            ) {
                $currentData[$field] = $this->translator->translate($referenceData[$field]);
            }
        }

        return $currentData;
    }

    private function checkIfFieldIsTranslatable($field, $form)
    {
        $isTranslatable = true;
        /**
         * CHECK IF TRANSLATABLE FIELD
         */
        $fieldConfig = null;
        if (isset($form->all()[$field])) {
            $fieldConfig = $form->all()[$field];
            if (null !== $fieldConfig) {
                $option = $fieldConfig->getConfig()->getOption('attr_translation_parameters');
                if (
                    isset($option['translatable'])
                    && false === $option['translatable']
                ) {
                    $isTranslatable = false;
                }
            }
        }

        return $isTranslatable;
    }

}
