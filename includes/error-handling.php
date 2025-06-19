<?php

global $vendi_errors;
$vendi_errors = [];

if (is_user_logged_in() && current_user_can('administrator')) {
    set_error_handler(
        static function (int $errno, string $errstr, ?string $errfile = null, ?int $errline = null): bool {
            if (!(error_reporting() & $errno)) {
                return false;
            }

            $stackTrace = debug_backtrace();

            $error = new class($errno, $errstr, $errfile, $errline, $stackTrace) {
                public string $errorTypeFriendly;

                public function __construct(
                    public int $errno,
                    public string $errstr,
                    public ?string $errfile,
                    public ?int $errline,
                    public array $stackTrace,
                ) {
                    $this->errorTypeFriendly = match ($this->errno) {
                        E_USER_ERROR => 'error',
                        E_WARNING, E_USER_WARNING => 'warning',
                        E_NOTICE, E_USER_NOTICE => 'notice',
                        E_RECOVERABLE_ERROR => 'recoverable',
                        E_DEPRECATED, E_USER_DEPRECATED => 'deprecated',
                        default => 'unknown',
                    };
                }
            };

            global $vendi_errors;
            $vendi_errors[] = $error;

            return true;
        },
    );

    add_filter(
        'wp_footer',
        static function (): void {
            global $vendi_errors;
            echo '<script>';
            echo 'const vendi_errors = '.json_encode($vendi_errors, JSON_THROW_ON_ERROR).';';
            echo '</script>';
            ?>
            <style>

                .vendi-errors[data-error-count] {
                    display: none;

                    &:not([data-error-count="0"]) {
                        cursor: pointer;
                        display: inherit;

                        & > a {
                            color: #fff;
                        }

                        &:hover {
                            & > div {
                                display: block;
                            }
                        }
                    }

                    & > div {
                        display: none;
                        background-color: #fff;
                        border: 1px solid #ccc;
                        padding: 10px;

                        & > ul > li {
                            padding-inline: 10px !important;
                            border-bottom: 1px solid #ccc;

                            &:last-child {
                                border-bottom: none;
                            }

                            details {
                                summary {
                                    display: grid;
                                    grid-template-columns: 100px 1fr;
                                    cursor: pointer;

                                    [data-error-type-friendly="error"] & > strong {
                                        color: red;
                                    }

                                    [data-error-type-friendly="warning"] & > strong {
                                        color: orange;
                                    }

                                    [data-error-type-friendly="notice"] & > strong {
                                        color: green;
                                    }

                                    [data-error-type-friendly="strict"] & > strong {
                                        color: blue;
                                    }

                                    [data-error-type-friendly="recoverable"] & > strong {
                                        color: purple;
                                    }

                                    [data-error-type-friendly="deprecated"] & > strong {
                                        color: gray;
                                    }
                                }

                                & > ul {
                                    margin-inline-start: 20px !important;

                                    & > li {
                                        display: grid;
                                        grid-template-columns: 200px 1fr 50px;
                                        gap: 10px;
                                    }
                                }
                            }
                        }
                    }
                }

                }
            </style>
            <script>
                /* global vendi_errors */
                (function (w) {
                    'use strict';

                    const
                        document = w.document,

                        createElement = (tag, text) => {
                            const el = document.createElement(tag);
                            el.textContent = text;
                            return el;
                        },

                        run = () => {
                            if (!vendi_errors) {
                                return;
                            }

                            const errorCount = vendi_errors.length;

                            const adminBar = document.getElementById('wp-toolbar');
                            if (!adminBar) {
                                throw 'Could not find admin tool bar to display errors';
                            }

                            const menuGroup = adminBar.querySelector('[role~="menu"]');
                            if (!menuGroup) {
                                throw 'Could not find menu group to display errors';
                            }

                            const newMenuItem = document.createElement('li');
                            newMenuItem.setAttribute('role', 'group');
                            newMenuItem.classList.add('menupop');
                            newMenuItem.classList.add('vendi-errors');
                            newMenuItem.setAttribute('data-error-count', errorCount);

                            const newMenuLink = document.createElement('a');
                            newMenuLink.setAttribute('role', 'menuitem');
                            // newMenuLink.setAttribute('href', '#');
                            newMenuLink.textContent = 'Vendi Errors (' + errorCount + ')';

                            const errorDetailsRegion = document.createElement('div');
                            const errorDetailsList = document.createElement('ul');

                            vendi_errors
                                .forEach(
                                    (error) => {
                                        const newErrorItem = document.createElement('li');
                                        newErrorItem.setAttribute('data-error-type', error.errno);
                                        newErrorItem.setAttribute('data-error-type-friendly', error.errorTypeFriendly);

                                        const details = document.createElement('details');
                                        const summary = document.createElement('summary');
                                        summary.append(
                                            createElement('strong', error.errorTypeFriendly),
                                            createElement('span', error.errstr),
                                        )

                                        details.appendChild(summary);

                                        const stackTraceList = document.createElement('ul');
                                        error.stackTrace
                                            .forEach(
                                                (stackItem) => {
                                                    const stackItemLi = document.createElement('li');
                                                    stackItemLi.append(
                                                        createElement('strong', stackItem['function']),
                                                        createElement('span', stackItem['file']),
                                                        createElement('span', stackItem['line']),
                                                    );
                                                    stackTraceList.appendChild(stackItemLi);
                                                }
                                            )
                                        ;

                                        details.appendChild(stackTraceList);

                                        newErrorItem.appendChild(details);

                                        errorDetailsList.appendChild(newErrorItem);
                                    }
                                )
                            ;

                            newMenuItem.appendChild(newMenuLink);
                            newMenuItem.appendChild(errorDetailsRegion);
                            errorDetailsRegion.appendChild(errorDetailsList);
                            menuGroup.appendChild(newMenuItem);

                        },

                        load = () => {
                            if (['complete', 'interactive'].includes(document.readyState)) {
                                run();
                            } else {
                                document.addEventListener('DOMContentLoaded', run);
                            }
                        }
                    ;

                    load();

                })(window);
            </script>
            <?php
        },
    );
}
