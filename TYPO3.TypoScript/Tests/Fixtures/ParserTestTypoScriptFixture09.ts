/**
 *  TypoScript Fixture 09
 *
 *  This fixture serves for testing the reference operator for
 *  objects, variables and properties.
 *
 */
namespace: default = T3_TYPO3_TypoScript
 
object1 = Text
object1.value = "Hello world!"

object3 = Text
object3.$cite =< object1

object2 =< object1
object2.value = "Quien busca el peligro, perece en él"

object3.value = 'Don Quijote dice: "$cite"'
