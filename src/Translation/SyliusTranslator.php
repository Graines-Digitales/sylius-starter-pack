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

    public function translateEntity($currentData, $referenceData, $form, $locale)
    {
        dump($currentData);
        dump($referenceData);
        // die;
        $translatedData = [];
        foreach($referenceData as $field=>$value) {
            /** SQUIZZ NON TRANSLATABLE FIELD */
            $isTranslatable = $this->checkIfFieldIsTranslatable($field, $form);
            if(false === $isTranslatable
                || !in_array($field, array_keys($form->all()))
            ) {
                continue;
            }
            $currentData[$field] = trim($currentData[$field]);
            $referenceData[$field] = trim($referenceData[$field]);
            if(empty($currentData[$field])
                && !empty($referenceData[$field])
            ) {
                $translatedData[$field] = $this->translator->translate($referenceData[$field]);
            } 
            // else {
            //     $translatedData[$field] = $translatedData[$field];
            // }
        }

        return $translatedData;
    }

    private function checkIfFieldIsTranslatable($field, $form)
    {
        $isTranslatable = true;
        /** CHECK IF TRANSLATABLE FIELD */
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

    public function clearComponent($component, $form)
    {
        foreach ($component as $field=>$value) {
            /** SQUIZZ NON TRANSLATABLE FIELD */
            $isTranslatable = $this->checkIfFieldIsTranslatable($field, $form);
            if(false === $isTranslatable
                || !in_array($field, array_keys($form->all()))
            ) {
                continue;
            }
            $component[$field] = null;
        }
        
        return $component;
    }

    public function translateComponents($currentData, $referenceData, $locale)
    {
        dump($currentData);
        $referenceDatacomponents = json_decode($referenceData['components'], true);
        $currentDataComponents = (!empty($currentData['components']))? json_decode($currentData['components'], true): json_decode($referenceData['components'], true);
    
        foreach($referenceDatacomponents as $index=>$referenceDatacomponent) {
            
            $slug = $referenceDatacomponent['data']['slug'];
            $currentData = [];
            foreach($currentDataComponents as $currentDataComponent) {
                if(isset($currentDataComponents[$index])) {
                    $key = array_search($slug, $currentDataComponents[$index]['data']);
                    // $key = array_search($slug, $currentDataComponents[$index]);
                    if(false !== $key){
                        $currentData = $currentDataComponents[$index];
                        break;
                    }
                }
            }
dump($currentData);
            $code = $referenceDatacomponent['code'];
            $elements = $this->container->getParameter('monsieurbiz.richeditor.config.ui_elements');
            $form = $this->container->get('form.factory')->create($elements[$code]['classes']['form']);
            if(empty($currentData)) {
               dump('ici');
                $currentData = [ 'code' => $code, 'data' => $referenceDatacomponent['data'] ];
                $currentData['data'] = $this->clearComponent($currentData['data'], $form);
            }
           dump($currentData['data']);
           dump($referenceDatacomponent['data']);
            $translated = $this->translateEntity($currentData['data'], $referenceDatacomponent['data'], $form, $locale);
            $currentDataComponents[$index]['code'] = $code;
            
            $currentDataComponents[$index]['data'] = array_merge($currentData['data'], $translated);

        }
        
        return $currentDataComponents;
    }
}
