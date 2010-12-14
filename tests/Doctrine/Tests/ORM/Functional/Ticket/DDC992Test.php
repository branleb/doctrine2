<?php

namespace Doctrine\Tests\ORM\Functional\Ticket;

require_once __DIR__ . '/../../../TestInit.php';

class DDC992Test extends \Doctrine\Tests\OrmFunctionalTestCase
{
    protected function setUp()
	 {
        parent::setUp();

        try {
            $this->_schemaTool->createSchema(array(
                $this->_em->getClassMetadata(__NAMESPACE__ . '\DDC992User'),
                $this->_em->getClassMetadata(__NAMESPACE__ . '\DDC992UserSetting')
            ));
        } catch(\Exception $e) {
			 //! Schema already existing
        }

        $this->_em->clear();
    }

    /**
     * @group DDC-992
     */
    public function testPersistSingle()
    {
		$user = new DDC992User;
		$user->setName('UnitCrap');
		$this->_em->persist($user);
		$this->_em->flush();

		$user->setPreference('Test', 'test');
		$this->_em->persist($user);
		$this->_em->flush();

		$user->getPreference('test');
    }

    /**
     * @group DDC-992
     */
    public function testPersistBoth()
    {

var_dump('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<');
		$user = new DDC992User;
		$user->setName('UnitCrap');
		$user->setPreference('Test', 'test');
		$this->_em->persist($user);
		$this->_em->flush();

		$user->getPreference('test');
var_dump('=========================================');
    }
}

/**
 * @Entity
 * @Table(name="user")
 */
class DDC992User
{
    /**
	  * @Id
     * @Column(type="integer", name="id")
	  * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
	  * @Column(type="string")
	  */
    private $name;

    /**
     * @OneToMany(targetEntity="DDC992UserSetting", mappedBy="user", cascade={"persist"})
     */
    private $preferences;

    public function __construct()
    {
        $this->preferences = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPreference($name, $value)
    {
		  if(isset($this->preferences[$name]) === false) {
			 $pref = new DDC992UserSetting;
			 $this->preferences[$name] = $pref;
			 $pref->setUser($this);
		  }

		  $this->preferences[$name]->setName($name);
		  $this->preferences[$name]->setValue($value);
    }

    public function getPreference($name)
    {
		  if(isset($this->preferences[$name])) {
			 return $this->preferences[$name]->getValue();
		  }
    }
}

/**
 * @Entity
 * @Table(name="user_setting")
 */
class DDC992UserSetting
{
    /**
     * @Id
     * @ManyToOne(targetEntity="DDC992User", inversedBy="preferences", cascade={"persist"})
     */
    private $user;

    /**
     * @Id
     * @Column(type="string", length=255)
	  * @GeneratedValue(strategy="NONE")
     */
    private $name;

    /**
     * @Column(type="string")
     */
    private $value;

    public function setUser(DDC992User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
