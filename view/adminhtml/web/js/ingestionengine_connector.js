/**
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */

/*global define*/
define(['jquery'], function ($) {
    'use strict';

    return {
        options: {
            type: null,
            step: 0,
            runUrl: null,
            runProductUrl: null,
            console: null,
            identifier: null,
            currentFamily: null,
            familyCount: 0,
            families: null
        },

        init: function (url, urlProduct, console) {
            this.options.runUrl = url;
            this.options.runProductUrl = urlProduct;
            this.console = $(console);
        },

        type: function (type, object) {
            this.options.type = type;
            this.step('type', $(object));
            this.options.currentFamily = null;
            this.options.familyCount = null;
            this.options.families = null;
        },

        step: function (type, object) {
            /* Reset step */
            this.options.step = 0;

            /* Reset identifier */
            this.options.identifier = null;

            /* Enable button */
            this.disabledImport(false);

            /* Reset Console */
            this.cleanConsole();

            /* Reset active element */
            $('.import-' + type).each(function () {
                $(this).removeClass('active');
            });

            /* Active element */
            object.addClass('active');
        },

        runProduct: function () {
            var ingestionengineConnector = this;
            $.ajax({
                url: ingestionengineConnector.options.runProductUrl,
                type: 'post',
                context: this,
                data: {
                    'identifier': ingestionengineConnector.options.identifier
                },
                success: function (response) {
                    if (response.message) {
                        ingestionengineConnector.disabledImport(true);
                        ingestionengineConnector.listElement(response.message, 'error');
                    } else {
                        ingestionengineConnector.options.families = response;
                        console.log(ingestionengineConnector.options.families);
                        ingestionengineConnector.run(ingestionengineConnector);
                    }
                }
            });
        },

        run: function (context = null) {
            var ingestionengineConnector = this;

            if (context != null) {
                var ingestionengineConnector = context;
            }

            if (ingestionengineConnector.options.currentFamily == null && ingestionengineConnector.options.families != null && ingestionengineConnector.options.families.length >= 1) {
                ingestionengineConnector.options.currentFamily = ingestionengineConnector.options.families[0];
            }

            ingestionengineConnector.disabledImport(true);

            if (ingestionengineConnector.options.type && ingestionengineConnector.options.runUrl) {
                $.ajax({
                    url: ingestionengineConnector.options.runUrl,
                    type: 'post',
                    context: this,
                    data: {
                        'code': ingestionengineConnector.options.type,
                        'step': ingestionengineConnector.options.step,
                        'identifier': ingestionengineConnector.options.identifier,
                        'family': ingestionengineConnector.options.currentFamily
                    },
                    success: function (response) {
                        ingestionengineConnector.removeWaiting();

                        if (response.identifier) {
                            ingestionengineConnector.options.identifier = response.identifier;
                        }

                        if (ingestionengineConnector.options.step === 0) {
                            ingestionengineConnector.listElement(response.comment, false);
                        }

                        if (response.message) {
                            if (response.status === false) {
                                ingestionengineConnector.listElement(response.message, 'error');
                            } else {
                                ingestionengineConnector.listElement(response.message, 'success');
                            }
                        }

                        if (response.continue) {
                            ingestionengineConnector.listElement(response.next, 'waiting');
                            ingestionengineConnector.options.step = ingestionengineConnector.options.step + 1;
                            ingestionengineConnector.run();
                        }

                        if (!response.continue && ingestionengineConnector.options.type == "product") {
                            ingestionengineConnector.options.identifier = null;
                            ingestionengineConnector.options.step = 0;
                            ingestionengineConnector.options.familyCount++;

                            if (ingestionengineConnector.options.families != null && ingestionengineConnector.options.families.hasOwnProperty(ingestionengineConnector.options.familyCount)) {
                                ingestionengineConnector.options.currentFamily = ingestionengineConnector.options.families[ingestionengineConnector.options.familyCount];
                                ingestionengineConnector.run();
                            }
                        }

                        ingestionengineConnector.console.scrollTop(100000);
                    }
                });
            }
        },

        removeWaiting: function () {
            this.console.find('li').removeClass('waiting');
        },

        listElement: function (content, elementClass) {
            this.console.append(
                '<li' + (elementClass ? ' class="' + elementClass + '"' : '') + '>' + content + '</li>'
            );
        },

        cleanConsole: function () {
            this.console.html(
                '<li class="selected">' +
                (this.options.type ? this.options.type + ' ' : '') +
                '</li>'
            );
        },

        disabledImport: function (enable) {
            $('.ingestionengine-connector-uploader').find('button').prop("disabled", enable);
        }
    }
});
