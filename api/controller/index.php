<?php

/**
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Controller for the basics operations to the files.                  *
 * The functions here, directly talks with the file.                   *
 *                                                                     *
 * They expect to receive the data with the same file structure,       *
 * so the input will be already sanitised.                             *
 *                                                                     *
 * Every direct call could potentially break the file structure and,   *
 * consequently, the site page.                                        *
 *                                                                     *
 * These controllers starts with two __ at the beginning.              *
 *                                                                     *
 * A function can return:                                              *
 * - The new/updated data;                                             *
 * - NULL if the resource is not found;                                *
 * - False if the resource is found but the action is not successful;  *
 * - True if there is nothing to return and the action is successful;  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
 */
