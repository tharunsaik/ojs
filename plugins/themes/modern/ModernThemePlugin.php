<?php

/**
 * @file plugins/themes/modern/ModernThemePlugin.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ModernThemePlugin
 *
 * @brief Modern child theme extending the Default theme with a clean,
 *        card-based visual design and a configurable homepage layout option.
 */

namespace APP\plugins\themes\modern;

class ModernThemePlugin extends \PKP\plugins\ThemePlugin
{
    /**
     * Initialize the theme's styles and options.
     *
     * Inherits all scripts (jQuery, Bootstrap, Swiper, FontAwesome) and parent
     * styles from the Default theme via setParent(). Only the additional
     * homepage layout option and override stylesheet are registered here.
     */
    public function init()
    {
        // Inherit the default theme's scripts, styles, and all its options
        // (baseColour, typography, showDescriptionInJournalIndex, etc.)
        $this->setParent('defaultthemeplugin');

        // New option: homepage layout (sidebar vs. full-width)
        $this->addOption('homepageLayout', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.modern.option.homepageLayout.label'),
            'description' => __('plugins.themes.modern.option.homepageLayout.description'),
            'options' => [
                [
                    'value' => 'sidebar',
                    'label' => __('plugins.themes.modern.option.homepageLayout.sidebar'),
                ],
                [
                    'value' => 'fullWidth',
                    'label' => __('plugins.themes.modern.option.homepageLayout.fullWidth'),
                ],
            ],
            'default' => 'sidebar',
        ]);

        // Register the modern override stylesheet (compiled after parent's stylesheet)
        $this->addStyle('modern-stylesheet', 'styles/index.less');

        // Inject LESS variables to toggle the homepage layout
        $additionalLessVariables = [];
        if ($this->getOption('homepageLayout') === 'fullWidth') {
            $additionalLessVariables[] = '@modern-sidebar-display:none;';
            $additionalLessVariables[] = '@modern-content-width:100%;';
            $additionalLessVariables[] = '@modern-content-float:none;';
        }
        if (!empty($additionalLessVariables)) {
            $this->modifyStyle('modern-stylesheet', ['addLessVariables' => join("\n", $additionalLessVariables)]);
        }
    }

    /**
     * Get the name of the settings file for new journal creation.
     *
     * @return string
     */
    public function getContextSpecificPluginSettingsFile()
    {
        return $this->getPluginPath() . '/settings.xml';
    }

    /**
     * Get the name of the settings file for site-wide installation.
     *
     * @return string
     */
    public function getInstallSitePluginSettingsFile()
    {
        return $this->getPluginPath() . '/settings.xml';
    }

    /**
     * Get the display name of this plugin.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return __('plugins.themes.modern.name');
    }

    /**
     * Get the description of this plugin.
     *
     * @return string
     */
    public function getDescription()
    {
        return __('plugins.themes.modern.description');
    }
}
