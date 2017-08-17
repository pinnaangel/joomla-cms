<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  User
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for JUserHelper.
 * Generated by PHPUnit on 2009-10-26 at 22:44:33.
 *
 * @package     Joomla.UnitTest
 * @subpackage  User
 * @since       12.1
*/
class JUserHelperTest extends TestCaseDatabase
{
	/**
	 * @var    JUserHelper
	 * @since  12.1
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		JFactory::$application = $this->getMockCmsApp();
		JFactory::$application->expects($this->any())
			->method('triggerEvent')
			->willReturn([]);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();
		TestReflection::setValue('JPluginHelper', 'plugins', null);
		parent::tearDown();
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  PHPUnit_Extensions_Database_DataSet_CsvDataSet
	 *
	 * @since   12.2
	 */
	protected function getDataSet()
	{
		$dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');

		$dataSet->addTable('jos_users', JPATH_TEST_DATABASE . '/jos_users.csv');
		$dataSet->addTable('jos_user_usergroup_map', JPATH_TEST_DATABASE . '/jos_user_usergroup_map.csv');
		$dataSet->addTable('jos_usergroups', JPATH_TEST_DATABASE . '/jos_usergroups.csv');

		return $dataSet;
	}

	/**
	 * Test cases for userGroups
	 *
	 * Each test case provides
	 * - integer  userid  a user id
	 * - array    group   user group, given as hash
	 *                    group_id => group_name,
	 *                    empty if undefined
	 * - array    error   error info, given as hash
	 *                    with indices 'code', 'msg', and
	 *                    'info', empty, if no error occurred
	 *
	 * @return array
	 */
	public function casesGetUserGroups()
	{
		return array(
			'unknownUser' => array(
				1000,
				array(),
				array(
					'code' => 500,
					'msg' => 'JLIB_USER_ERROR_UNABLE_TO_LOAD_USER',
					'info' => ''),
			),
			'publisher' => array(
				43,
				array(5 => 5),
				array(),
			),
			'manager' => array(
				44,
				array(6 => 6),
				array(),
			),
		);
	}

	/**
	 * TestingGetUserGroups().
	 *
	 * @param   integer  $userid    User ID
	 * @param   mixed    $expected  User object or empty array if unknown
	 * @param   array    $error     Expected error info
	 *
	 * @dataProvider casesGetUserGroups
	 * @covers  JUserHelper::getUserGroups
	 * @return  void
	 */
	public function testGetUserGroups($userid, $expected, $error)
	{
		$this->assertEquals(
			$expected,
			JUserHelper::getUserGroups($userid)
		);
	}

	/**
	 * Test cases for userId
	 *
	 * @return array
	 */
	public function casesGetUserId()
	{
		return array(
			'admin' => array(
				'admin',
				42,
				array(),
			),
			'unknown' => array(
				'unknown',
				null,
				array(),
			),
		);
	}

	/**
	 * TestingGetUserId().
	 *
	 * @param   string   $username  User name
	 * @param   integer  $expected  Expected user id
	 * @param   array    $error     Expected error info
	 *
	 * @dataProvider casesGetUserId
	 * @covers  JUserHelper::getUserId
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	public function testGetUserId($username, $expected, $error)
	{
		$this->assertEquals(
			$expected,
			JUserHelper::getUserId($username)
		);

	}

	/**
	 * Test cases for testAddUserToGroup
	 *
	 * @return array
	 */
	public function casesAddUserToGroup()
	{
		return array(
			'publisher' => array(
				43,
				6,
				true
			),
			'manager' => array(
				44,
				6,
				true
			),
		);
	}
	/**
	 * Testing addUserToGroup().
	 *
	 * @param   string   $userId    User id
	 * @param   integer  $groupId   Group to add user to
	 * @param   boolean  $expected  Expected params
	 *
	 * @dataProvider casesAddUsertoGroup
	 * @covers  JUserHelper::addUsertoGroup
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testAddUserToGroup($userId, $groupId, $expected)
	{
		$this->assertEquals(
			$expected,
			JUserHelper::addUserToGroup($userId, $groupId)
		);
	}

	/**
	 * Testing addUserToGroup() with expected exception.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @expectedException  RuntimeException
	 * @covers  JUserHelper::addUsertoGroup
	 */
	public function testAddUserToGroupException()
	{
		JUserHelper::addUserToGroup(44, 99);
	}

	/**
	 * Test cases for testRemoveUserFromGroup
	 *
	 * @return array
	 */
	public function casesRemoveUserFromGroup()
	{
		return array(
			'publisher' => array(
				43,
				8,
				true
			),
			'manager' => array(
				44,
				6,
				true
			),
		);
	}

	/**
	 * Testing removeUserFromGroup().
	 *
	 * @param   string   $userId    User id
	 * @param   integer  $groupId   Group to remove user from
	 * @param   boolean  $expected  Expected params
	 *
	 * @dataProvider casesRemoveUserFromGroup
	 * @covers  JUserHelper::removeUserFromGroup
	 * @return  void
	 */
	public function testRemoveUserFromGroup($userId, $groupId, $expected)
	{
		$this->assertEquals(
			$expected,
			JUserHelper::removeUserFromGroup($userId, $groupId)
		);
	}

	/**
	 * Test cases for testActivateUser
	 *
	 * @return array
	 */
	public function casesActivateUser()
	{
		return array(
			'Valid User' => array(
				'30cc6de70fb18231196a28dd83363d57',
				true
			),
			'Invalid User' => array(
				'30cc6de70fb18231196a28dd83363d72',
				false
			),
		);
	}

	/**
	 * Testing activateUser().
	 *
	 * @param   string   $activation  Activation string
	 * @param   boolean  $expected    Expected params
	 *
	 * @dataProvider casesActivateUser
	 * @covers  JUserHelper::activateUser
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testActivateUser($activation, $expected)
	{
		// Configure the container
		$container = (new \Joomla\DI\Container)
			->set('dispatcher', $this->getMockDispatcher())
			->set('db', static::$driver);

		JFactory::$container = $container;

		$this->assertEquals(
			JUserHelper::activateUser($activation),
			$expected
		);
	}

	/**
	 * Testing hashPassword().
	 *
	 * @covers  JUserHelper::hashPassword
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testHashPassword()
	{
		$this->assertEquals(
			strpos(JUserHelper::hashPassword('mySuperSecretPassword'), '$2y$'),
			0,
			'Joomla currently hashes passwords using BCrypt, verify the correct prefix is present'
		);
	}

	/**
	 * Test cases for testVerifyPassword
	 *
	 * @return  array
	 */
	public function casesVerifyPassword()
	{
		// Plaintext password, hashed password
		return [
			'PHPass' => [
				'mySuperSecretPassword',
				'$P$D6vpNa203LlaQUah3KcVQIhgFZ4E6o1'
			],
			'BCrypt' => [
				'mySuperSecretPassword',
				'$2y$10$0GfV1d.dfYvWu83ZKFD4surhsaRpVjUZqhG9bShmPcSnmqwCes/lC'
			],
			'SHA256' => [
				'mySuperSecretPassword',
				'{SHA256}972c5f5b845306847cb4bf941b7a683f1a828f48c46abef8b9ae4dac9798b1d5:oeLpBZ2sFJwLZmm4'
			],
			'Joomla MD5' => [
				'mySuperSecretPassword',
				'693560686f4d591d8dd5e34006442061'
			],
			// See https://github.com/joomla/joomla-cms/pull/5551
			'Joomla 1.0' => [
				'test',
				'098f6bcd4621d373cade4e832627b4f6:'
			],
			'Pre PHPass' => [
				'mySuperSecretPassword',
				'fb7b0a16d7e0e6706c0f962832e1fdd8:vQnUrofbvGRcBR6l502Bt8nioKj8MObh'
			]
		];
	}

	/**
	 * Testing verifyPassword().
	 *
	 * @param   string   $password  The plaintext password to check.
	 * @param   string   $hash      The hash to verify against.
	 *
	 * @dataProvider casesVerifyPassword
	 * @covers  JUserHelper::verifyPassword
	 * @return  void
	 *
	 * @since   3.2
	 * @link    https://github.com/joomla/joomla-cms/pull/5551
	 */
	public function testVerifyPassword($password, $hash)
	{
		$this->assertTrue(
			JUserHelper::verifyPassword($password, $hash),
			'Properly verifies a password'
		);
	}
}
