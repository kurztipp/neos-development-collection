<?php
declare(encoding = 'utf-8');

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * Testcase for the TypoScript Parser
 * 
 * @package		TypoScript
 * @version 	$Id:T3_FLOW3_Component_ManagerTest.php 201 2007-03-30 11:18:30Z robert $
 * @copyright	Copyright belongs to the respective authors
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class T3_TypoScript_ParserTest extends T3_Testing_BaseTestCase {
	
	/**
	 * @var T3_TypoScript_Parser
	 */
	protected $parser;
	
	/**
	 * Sets up this test case
	 *
	 * @author  Robert Lemke <robert@typo3.org>
	 */
	protected function setUp() {
		$this->parser = $this->componentManager->getComponent('T3_TypoScript_Parser');
	}

	/**
	 * Checks if the TypoScript parser returns an object tree
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserReturnsObjectTreeArray() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture01.ts', FILE_TEXT);
		$objectTree = $this->parser->parse($sourceCode);
		$this->assertType('array', $objectTree, 'The TypoScript parser did not return an array.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 01
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture01() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture01.ts', FILE_TEXT);

		$expectedObjectTree['test'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['test']->setValue('Hello world!');
		$expectedObjectTree['secondTest'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['secondTest']->setValue(23);
		
		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 01.');
		$this->assertSame($expectedObjectTree['secondTest']->getValue(), $actualObjectTree['secondTest']->getValue(), 'The numeric value was not really the same after parsing fixture 01.');
	}

	/**
	 * Checks if an arbitrary string after a namespace declaration throws an exception
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserThrowsSyntaxExceptionStringAfterNamespaceDeclaration() {
		$sourceCode = "namespace: cms=T3_TYPO3_TypoScript xyz";
		try {
			$this->parser->parse($sourceCode);		
			$this->fail('String after namespace declaration did not throw an exception.');
		} catch (Exception $exception) {
			
		}
	}
	
	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 02
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture02() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture02.ts', FILE_TEXT);

		$expectedObjectTree['myObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['myObject']->setValue("Sorry, we're closed.");
		$expectedObjectTree['anotherObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['anotherObject']->setValue('And I said: "Hooray"');
		$expectedObjectTree['kaspersObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['kaspersObject']->setValue('The end of this line is a backslash\\');

		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 02.');
	}
	
	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 03
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture03() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture03.ts', FILE_TEXT);

		$expectedObjectTree['myObject']['mySubObject']['mySubSubObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['myObject']['mySubObject']['mySubSubObject']->setValue("Espresso is a fine beverage.");

		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 03.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 04
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture04() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture04.ts', FILE_TEXT);

		$expectedObjectTree['myArrayObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_ContentArray');
		$expectedObjectTree['myArrayObject'][10] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['myArrayObject'][10]->setValue('Hello ');
		$expectedObjectTree['myArrayObject'][20] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['myArrayObject'][20]->setValue('world!');
		$expectedObjectTree['myArrayObject'][30] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_ContentArray');
		$expectedObjectTree['myArrayObject'][30][20] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_ContentArray');
		$expectedObjectTree['myArrayObject'][30][20][10] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['myArrayObject'][30][20][10]->setValue('Huh?');		
		$expectedObjectTree['anotherObject']['sub1']['sub2']['sub3'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_ContentArray');
		$expectedObjectTree['anotherObject']['sub1']['sub2']['sub3'][1] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['anotherObject']['sub1']['sub2']['sub3'][1]->setValue('Yawn');

		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 04.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 05
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture05() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture05.ts', FILE_TEXT);

		$expectedObjectTree['firstObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['firstObject']->setValue('Go outside. The graphics are AMAZING!');
		$expectedObjectTree['secondObject']['subObject'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['secondObject']['subObject']->setValue('27°C and a blue sky.');
		$expectedObjectTree['thirdObject']['subObject']['subSubObject']['someMessage'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['thirdObject']['subObject']['subSubObject']['someMessage']->setValue('Fully or hard tail?');

		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 05.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 06
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture06() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture06.ts', FILE_TEXT);

		$expectedObjectTree['object1'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object1']->setValue('Hello world');
		$expectedObjectTree['object2'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object2']->setValue('Hello world');
		$expectedObjectTree['object3'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object3']->setValue("I didn't have a coffee yet!");
		$expectedObjectTree['object4'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object4']->setValue("Hello, Kasper Skårhøj!");
		
		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 06.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 07
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture07() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture07.ts', FILE_TEXT);

		$expectedObjectTree['object2'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object2']->setValue('');
		$expectedObjectTree['object3'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object3']->setValue('');
		
		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 07.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 08
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture08() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture08.ts', FILE_TEXT);

		$expectedObjectTree['object1'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object1']->setValue('Hello world!');
		$expectedObjectTree['object2'] = clone $expectedObjectTree['object1'];
		$expectedObjectTree['lib']['object3'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['lib']['object3']->setValue('Another message');
		$expectedObjectTree['lib']['object4'] = clone $expectedObjectTree['lib']['object3'];
		$expectedObjectTree['lib']['object5'] = clone $expectedObjectTree['lib']['object3'];
		$expectedObjectTree['lib']['object6'] = clone $expectedObjectTree['object1'];
		$expectedObjectTree['object7'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object7']->setValue($expectedObjectTree['object1']->getValue());
		$expectedObjectTree['object8'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object8']->setValue('I say "Hello world!"');
		
		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 08.');
	}

	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 09
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture09() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture09.ts', FILE_TEXT);
		
		$expectedObjectTree['object1'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object1']->setValue('Quien busca el peligro, perece en él');
		$expectedObjectTree['object2'] = $expectedObjectTree['object1'];
		$expectedObjectTree['object3'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object3']->setValue('Don Quijote dice: "Quien busca el peligro, perece en él"');
				
		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 09.');
		$this->assertSame($actualObjectTree['object1'], $actualObjectTree['object2'], 'The two reference objects are not identical after parsing fixture 09.');
	}	
	
	/**
	 * checks if the object tree returned by the TypoScript parser reflects source code fixture 10
	 *
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parserCorrectlyParsesFixture10() {
		$sourceCode = file_get_contents(dirname(__FILE__) . '/Fixtures/ParserTestTypoScriptFixture10.ts', FILE_TEXT);
		$processorObject = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Processors');
		
		$propertyProcessorChain = $this->componentManager->getComponent('T3_TypoScript_ProcessorChain');
		$expectedObjectTree['object1'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object1']->setValue('Hello world!');
		$expectedObjectTree['object1']->setPropertyProcessorChain('value', $propertyProcessorChain);
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('<strong>', '</strong>'));
		$propertyProcessorChain->setProcessorInvocation(1, $processorInvocation);	
		
		$propertyProcessorChain = $this->componentManager->getComponent('T3_TypoScript_ProcessorChain');
		$expectedObjectTree['object2'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object2']->setValue('Bumerang');
		$expectedObjectTree['object2']->setPropertyProcessorChain('value', $propertyProcessorChain);
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('ein ', ';'));
		$propertyProcessorChain->setProcessorInvocation(1, $processorInvocation);	
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('War ', ''));
		$propertyProcessorChain->setProcessorInvocation(3, $processorInvocation);	
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('einmal (vielleicht auch zweimal) ', ''));
		$propertyProcessorChain->setProcessorInvocation(2, $processorInvocation);	

		$propertyProcessorChain = $this->componentManager->getComponent('T3_TypoScript_ProcessorChain');
		$expectedObjectTree['object3'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object3']->setValue('345');
		$expectedObjectTree['object3']->setPropertyProcessorChain('value', $propertyProcessorChain);
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('2', '6'));
		$propertyProcessorChain->setProcessorInvocation(1, $processorInvocation);	
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('1', '789 ...'));
		$propertyProcessorChain->setProcessorInvocation(2, $processorInvocation);	
		
		$propertyProcessorChain = $this->componentManager->getComponent('T3_TypoScript_ProcessorChain');
		$expectedObjectTree['object4'] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_ContentArray');
		$expectedObjectTree['object4'][10] = $this->componentManager->getComponent('T3_TYPO3_TypoScript_Text');
		$expectedObjectTree['object4'][10]->setValue('cc');
		$expectedObjectTree['object4'][10]->setPropertyProcessorChain('value', $propertyProcessorChain);
		$processorInvocation = $this->componentManager->getComponent('T3_TypoScript_ProcessorInvocation', $processorObject, 'processor_wrap', array('su', 'ess'));
		$propertyProcessorChain->setProcessorInvocation(1, $processorInvocation);	
		
		$actualObjectTree = $this->parser->parse($sourceCode);
		$this->assertEquals($expectedObjectTree, $actualObjectTree, 'The object tree was not as expected after parsing fixture 10.');
	}	}
?>