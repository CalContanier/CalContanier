<?php
/**
 * Author: 沧澜
 * Date: 2020-09-21
 */

namespace CalContainer\Tests\Components;

use CalContainer\Components\Annotation\TagAnnotation;
use PHPUnit\Framework\TestCase;

class AnnotationTest extends TestCase
{

    public function annotationTestData()
    {
        $doc1 = <<<DOC
/**
 * @t1 text
 * @t2(t2-text)
 * @t3('t3-text')
 * @t4("t4-text")
 * @t5 ("t5-text")
 * @t6 ("t6-text")
 */
DOC;

        $doc2 = <<<DOC
/**
 * @t1
 * @t2()
 * @t3 ()
 * @t4 ( )
 * @t4('')
 * @t5("")
 * @t6 ('')
 * @t7 ("")
 * @t8 (' ')
 * @t9 (" ")
 * @t10    ()
 */    

DOC;

        return [
            [$doc1, range(1,6), ['text', 't2-text', 't3-text', 't4-text', 't5-text', 't6-text']],
            [$doc2, range(1, 10), [4 => ' ', 8 => ' ', 9 => ' ']]
        ];
    }


    /**
     * @dataProvider annotationTestData
     * @param string $docComment
     * @param array $keys
     * @param array $keyValues
     */
    public function testAnnotation($docComment, $keys, $keyValues)
    {
        $tagAnnotation = new TagAnnotation();
        $parses = $tagAnnotation->parse($docComment);
        






//        $this->assertArrayHasKey('',)

    }
}