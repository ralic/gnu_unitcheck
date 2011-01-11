<?php

    /**
     * This is the reports page
     *
     * Copyright 	(c) 2011 Tom Kaczocha
     *
     * This file is part of UnitCheck.
     *
     * UnitCheck is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     *
     * UnitCheck is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with UnitCheck.  If not, see <http://www.gnu.org/licenses/>.
     *
     *
     */
    require_once('../includes/initialise.php');

    require_once("../tests/databaseTests.php");
    require_once("testCleanup.php");

    $_SESSION['title'] = 'Test Results';
    
    $testNames = array();
    $testResults = array();
    $errMessages = array();

    // run PRE-cleanup
    runCleanup();

    databaseCreatedTest();
    settingsTableCreatedTest();
    usersTableCreatedTest();
    projectTableCreatedTest();
    testsTableCreatedTest();
    

    // run POST-cleanup
    runCleanup();

    UnitCheckHeader::printHeader();
    

    // PRINT TEST RESULTS
    $unitCheck->printResults();

    

    UnitCheckFooter::printFooter();

    //header('Location: reports.php');

?>
