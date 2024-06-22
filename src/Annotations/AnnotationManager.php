<?php

namespace ORM\Annotations;

use ReflectionClass;

class AnnotationManager
{
    public static function getClassAnnotations(string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        $annotations = [];

        $classAnnotations = self::getClassAnnotationsFromDocComment($reflectionClass->getDocComment());
        if (isset($classAnnotations['ORM\\Table'])) {
            $annotations['table'] = new Table($classAnnotations['ORM\\Table']);
        }

        $propertyAnnotations = self::getClassPropertiesAnnotations($reflectionClass);
        $annotations['columns'] = $propertyAnnotations;

        return $annotations;
    }

    private static function getClassPropertiesAnnotations(ReflectionClass $reflectionClass)
    {
        $propertyAnnotations = [];
        $currentClass = $reflectionClass;

        do {
            foreach ($currentClass->getProperties() as $property) {
                $propertyName = $property->getName();

                $propertyDocComment = $property->getDocComment();

                if ($propertyDocComment) {
                    $annotations = self::getClassAnnotationsFromDocComment($propertyDocComment);

                    if (isset($annotations['ORM\\Column'])) {
                        $columnAnnotation = $annotations['ORM\\Column'];
                        $propertyAnnotations[$propertyName] = new Column($columnAnnotation);
                    }
                }
            }
            $currentClass = $currentClass->getParentClass();
        } while ($currentClass !== false);

        return $propertyAnnotations;
    }

    private static function getClassAnnotationsFromDocComment($docComment)
    {
        $annotations = [];

        preg_match_all('/@(ORM\\\\)?(\w+)\b\s*(\((?:[^()]|(?3))*\))?/', $docComment, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $ormPrefix = $match[1] ?? '';
            $annotationName = $match[2];

            $params = isset($match[3]) ? self::parseParams($match[3]) : [];

            $annotations[$ormPrefix . $annotationName] = $params;
        }

        return $annotations;
    }

    private static function parseParams($paramString)
    {
        $params = [];
        preg_match_all('/(\w+)\s*=\s*([\'"])(.*?)\2(?=[\s,)]|$)/', $paramString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $params[$match[1]] = $match[3];
        }
        return $params;
    }
}
