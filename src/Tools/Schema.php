<?php

namespace App\Tools;

use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Service permettant de travailler sur les 
 * différents schémas des formulaires
 */
class Schema 
{
    /**
    * @var ContainerInterface
    */
    protected $container;
    
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    protected $validator;

    private $asserts = [];
    
    /**
     * Undocumented function
     *
     * @param ContainerInterface $container
     * @param EntityManagerInterface $manager
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        ValidatorInterface $validator
    ){
        $this->container = $container;
        $this->manager = $manager;
        $this->translator = $translator;
        $this->validator = $validator;
    }

    /**
     * Retourne les asserts 
     * récupérer via les annotations
     * d'une entité donnée
     * après avoir executer ->asserts('entity')
     *
     * @return array
     */
    public function get(): array
    {
        return $this->asserts;
    }

    /**
     * Retourne les asserts 
     * au format jquery.validate
     * récupérer via les annotations
     * d'une entité donnée
     * après avoir executer ->asserts('entity')
     *
     * @return array
     */
    public function getJqueryValidateRules(): array
    {
        /**
         * Refacto à faire pour retirer le spécif
         * 
         */
        
        $rules = [];
        $messages = [];
        foreach ($this->asserts as $field=>$assert) {
            // dump($field);
            $messageItems = [];
            foreach ($assert as $key=>$value) {
              
                if('notblank' === $key || 'notnull' === $key) {
                    $rules[$field]['required'] = true;
                    $messageItems['required'] = $value['message'];
                }
                if('regex' === $key) {
                    if('phone' === $field) {
                        $rules[$field]['digits'] = true;
                        $messageItems['digits'] = $value['message'];
                    } else {
                        $rules[$field][$key] = true;
                        $messageItems[$key] = $value['message'];
                    }
                    
                }
                if('expression' === $key) {
                    $rules[$field][$key] = true;
                    $messageItems[$key] = $value['message'];
                }
                if('email' === $key) {
                    $rules[$field][$key] = true;
                    $messageItems[$key] = $value['message'];
                }
                if('url' === $key) {
                    $rules[$field][$key] = true;
                    $messageItems[$key] = $value['message'];
                }
                if('notequalto' === $key) {
                    $rules[$field]['valueNotEquals'] = "-1";
                    $messageItems['valueNotEquals'] = $value['message'];
                }
                if('file' === $key) {
       
                    $rules['files[]'] = [
                        "required" => true,
                        "extension" => "jpg|jpeg|png|pdf",
                        "filesize" => 20971520 
                    ];
                    $messageItems['valueNotEquals'] = 'une erreur est survenue';
                   
                
                   
                }
               
            }
            $messages[$field] = $messageItems;
        }
        // dump('allo');
// die;
        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }

    public function getConstraintsFromFormType($form): self
    {
         

        /**
         * @TODO : CHAINTIER À OUVRIR COMMENT RÉCUPÉRER LES CONSTRAINTS VIA UN FORM TYPE
         * OU ALORS COMMENT RÉCUPÉRER LES RELATUIONS VIA UN FORM TYPE
         */
        // $entityName = u($form)->camel()->title();
        // $entityNameFormType = 'App\Form\\' . $entityName . 'Type';
        // $form = $this->container->get('form.factory')->create($entityNameFormType, null, []);
        // foreach($form->getIterator() as $key=>$field) {
        //     dump($key);
        //     dump($field);
        //     $a = $this->validator->getMetadataFor($field);
        //     dump($a);
        // }   

        // dump();
        // die;

        /**
         * @TODO : Bien sur ceci est temporaire
         */
        if('message' === $form) {
            // DUMP('ALLO');
            $this-> getConstraintsFromEntity('message');
            // $this-> getConstraintsFromEntity('localBusiness');
            $this-> getConstraintsFromEntity('person');
            // $this-> getConstraintsFromEntity('mediaObject');
            
        }
// dump($this->asserts);
// die;
        return $this;
    }

    /**
     * Récupère les asserts depuis les annotations 
     * d'une entité donnée
     *
     * @param [type] $entity
     * @return self
     */
    public function getConstraintsFromEntity($entity): self
    {
        $entityName = u($entity)->camel()->title();
        $className = 'App\Entity\\' . $entityName;
        $repositoryName = 'App:' . $entityName;

        $entities = $this->manager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        if (!array_search($className, $entities, true)) {
            return [];
        }
 
        $serializer = $this->container->get('serializer');
        $repository = $this->manager->getRepository($repositoryName);
        $metadata = $this->manager->getClassMetadata($repositoryName);

        $asserts = $this->findAssertsFormMetada($metadata, $repository->getClassName());
        $asserts = $serializer->normalize($asserts, null);
    //    dump($asserts);
        foreach ($asserts as $k=>$assert) {
            foreach ($assert as $key=>$value) {
                $message = null;
                if(isset($value['message'])) {
                    $message = $this->translator->trans($value['message'], [], 'validators');
                }
                $this->asserts[$k][$key]['message'] = $message; 
            }
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param [type] $metadata
     * @param [type] $className
     * @return array
     */
    private function findAssertsFormMetada($metadata, $className): array
    {
        $annotationReader = new AnnotationReader();
        $asserts = [];
  
        foreach ($metadata->fieldMappings as $field) {

            $reflectionProperty = new \ReflectionProperty($className, $field['fieldName']);
            $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
            $indexName = (string) u($reflectionProperty->getName())->camel();
            foreach ($propertyAnnotations as $property) {
                $reflectionClass = new \ReflectionClass($property);
                if (false !== $reflectionClass->getParentClass()) {
                    $parentClassName = strtolower($reflectionClass->getParentClass()->getShortName());
                    if ('constraint' == $parentClassName) {
                       
                        $asserts[$indexName][strtolower($reflectionClass->getShortName())] = $property;
                    }
                }
            } 
        }
        
        foreach ($metadata->associationMappings as $field) {

            $reflectionProperty = new \ReflectionProperty($className, $field['fieldName']);
            $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
            $indexName = (string) u($reflectionProperty->getName())->camel();
            foreach ($propertyAnnotations as $property) {
                $reflectionClass = new \ReflectionClass($property);
                
                $namespace = explode('\\', $reflectionClass->getNamespaceName());
    

                
                // if (false !== $reflectionClass->getParentClass()) {
                    // $parentClassName = strtolower($reflectionClass->getParentClass()->getShortName());
                    $parentClassName = strtolower(end($namespace));
                    if ('constraints' == $parentClassName) {
                       
                        $asserts[$indexName][strtolower($reflectionClass->getShortName())] = $property;
                    }
                // }
            } 
        }

        return $asserts;
    }
}