@aware(['component'])

@php
    $theme = $component->getTheme();
@endphp

@if ($component->hasConfigurableAreaFor('before-toolbar'))
    @include($component->getConfigurableAreaFor('before-toolbar'), $component->getParametersForConfigurableArea('before-toolbar'))
@endif

@if ($theme === 'tailwind')
    <div class="px-4 mb-4 md:flex md:justify-between md:p-0">
        <div class="w-full mb-4 space-y-4 md:mb-0 md:w-2/4 md:flex md:space-y-0 md:space-x-2">
            @if ($component->hasConfigurableAreaFor('toolbar-left-start'))
                @include($component->getConfigurableAreaFor('toolbar-left-start'), $component->getParametersForConfigurableArea('toolbar-left-start'))
            @endif

            @if ($component->reorderIsEnabled())
                <button
                    wire:click="{{ $component->currentlyReorderingIsEnabled() ? 'disableReordering' : 'enableReordering' }}"
                    type="button"
                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm md:w-auto hover:text-gray-500 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 active:bg-gray-50 active:text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                >
                    @if ($component->currentlyReorderingIsEnabled())
                        @lang('Done Reordering')
                    @else
                        @lang('Reorder')
                    @endif
                </button>
            @endif

            @if ($component->searchIsEnabled() && $component->searchVisibilityIsEnabled())
                <div class="flex rounded-md shadow-sm">
                    <input
                        wire:model{{ $component->getSearchOptions() }}="{{ $component->getTableName() }}.search"
                        placeholder="{{ __('Search') }}"
                        type="text"
                        class="px-2 block w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 dark:bg-gray-700 dark:text-white dark:border-gray-600 @if ($component->hasSearch()) rounded-none rounded-l-md focus:ring-0 focus:border-gray-300 @else focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md @endif"
                    />

                    @if ($component->hasSearch())
                        <span wire:click.prevent="clearSearch" class="inline-flex items-center px-3 text-gray-500 border border-l-0 border-gray-300 cursor-pointer bg-gray-50 rounded-r-md sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    @endif
                </div>
            @endif

            @if ($component->filtersAreEnabled() && $component->filtersVisibilityIsEnabled() && $component->hasVisibleFilters())
                <div
                    @if ($component->isFilterLayoutPopover())
                        x-data="{ open: false }"
                        x-on:keydown.escape.stop="open = false"
                        x-on:mousedown.away="open = false"
                    @endif

                    class="relative block text-left md:inline-block"
                >
                    <div>
                        <button
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"

                            @if ($component->isFilterLayoutPopover())
                                x-on:click="open = !open"
                                aria-haspopup="true"
                                x-bind:aria-expanded="open"
                                aria-expanded="true"
                            @endif

                            @if ($component->isFilterLayoutSlideDown())
                                x-on:click="filtersOpen = !filtersOpen"
                            @endif
                        >
                            @lang('Filters')

                            @if ($count = $component->getFilterBadgeCount())
                                <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-indigo-100 text-indigo-800 capitalize dark:bg-indigo-200 dark:text-indigo-900">
                                    {{ $count }}
                                </span>
                            @endif

                            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </button>
                    </div>

                    @if ($component->isFilterLayoutPopover())
                        <div
                            x-cloak
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 z-50 w-full mt-2 origin-top-left bg-white divide-y divide-gray-100 rounded-md shadow-lg md:w-56 ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-700 dark:text-white dark:divide-gray-600"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="filters-menu"
                        >
                            @foreach($component->getFilters() as $filter)
                                @if($filter->isVisibleInMenus())
                                    <div class="py-1" role="none">
                                        <div class="block px-4 py-2 space-y-1 text-sm text-gray-700" role="menuitem">
                                            <label for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
                                                class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
                                                {{ $filter->getName() }}
                                            </label>

                                            {{ $filter->render($component) }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if ($component->hasAppliedVisibleFiltersWithValuesThatCanBeCleared())
                                <div class="block px-4 py-3 text-sm text-gray-700 dark:text-white" role="menuitem">
                                    <button
                                        wire:click.prevent="setFilterDefaults"
                                        x-on:click="open = false"
                                        type="button"
                                        class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:border-gray-500"
                                    >
                                        @lang('Clear')
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-left-end'))
                @include($component->getConfigurableAreaFor('toolbar-left-end'), $component->getParametersForConfigurableArea('toolbar-left-end'))
            @endif
        </div>

        <div class="space-y-4 md:flex md:items-center md:space-y-0 md:space-x-2">
            @if ($component->hasConfigurableAreaFor('toolbar-right-start'))
                @include($component->getConfigurableAreaFor('toolbar-right-start'), $component->getParametersForConfigurableArea('toolbar-right-start'))
            @endif

            @if ($component->showBulkActionsDropdown())
                <div class="w-full mb-4 md:w-auto md:mb-0">
                    <div
                        x-data="{ open: false }"
                        @keydown.window.escape="open = false"
                        x-on:click.away="open = false"
                        class="relative z-10 inline-block w-full text-left md:w-auto"
                    >
                        <div>
                            <span class="rounded-md shadow-sm">
                                <button
                                    x-on:click="open = !open"
                                    type="button"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                                    aria-haspopup="true"
                                    x-bind:aria-expanded="open"
                                    aria-expanded="true"
                                >
                                    @lang('Bulk Actions')

                                    <svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </span>
                        </div>

                        <div
                            x-cloak
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 w-full mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg md:w-48 ring-1 ring-black ring-opacity-5 focus:outline-none"
                        >
                            <div class="bg-white rounded-md shadow-xs dark:bg-gray-700 dark:text-white">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    @foreach($component->getBulkActions() as $action => $title)
                                        <button
                                            wire:click="{{ $action }}"
                                            wire:key="bulk-action-{{ $action }}-{{ $component->getTableName() }}"
                                            type="button"
                                            class="flex items-center block w-full px-4 py-2 space-x-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 dark:text-white dark:hover:bg-gray-600"
                                            role="menuitem"
                                        >
                                            <span>{{ $title }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($component->columnSelectIsEnabled())
                <div class="@if ($component->getColumnSelectIsHiddenOnMobile()) hidden sm:block @elseif ($component->getColumnSelectIsHiddenOnTablet()) hidden md:block @endif mb-4 w-full md:w-auto md:mb-0 md:ml-2">
                    <div
                        x-data="{ open: false }"
                        @keydown.window.escape="open = false"
                        x-on:click.away="open = false"
                        class="relative inline-block w-full text-left md:w-auto"
                        wire:key="column-select-button-{{ $component->getTableName() }}"
                    >
                        <div>
                            <span class="rounded-md shadow-sm">
                                <button
                                    x-on:click="open = !open"
                                    type="button"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                                    aria-haspopup="true"
                                    x-bind:aria-expanded="open"
                                    aria-expanded="true"
                                >
                                    @lang('Columns')

                                    <svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </span>
                        </div>

                        <div
                            x-cloak
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 w-full mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 md:w-48 focus:outline-none"
                        >
                            <div class="bg-white rounded-md shadow-xs dark:bg-gray-700 dark:text-white">
                                <div class="p-2" role="menu" aria-orientation="vertical" aria-labelledby="column-select-menu">
                                    <div>
                                        <label
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center px-2 py-1 disabled:opacity-50 disabled:cursor-wait"
                                        >
                                            <input
                                                class="text-indigo-600 transition duration-150 ease-in-out border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600 disabled:opacity-50 disabled:cursor-wait"
                                                @if($component->allDefaultVisibleColumnsAreSelected())
                                                    checked
                                                    wire:click="deselectAllColumns"
                                                @else
                                                    unchecked
                                                    wire:click="selectAllColumns"
                                                @endif
                                                wire:loading.attr="disabled"
                                                type="checkbox"
                                            />
                                            <span class="ml-2">{{ __('All Columns') }}</span>
                                        </label>
                                    </div>
                                    @foreach($component->getColumns() as $column)
                                        @if ($column->isVisible() && $column->isSelectable())
                                            <div wire:key="columnSelect-{{ $loop->index }}-{{ $component->getTableName() }}">
                                                <label
                                                    wire:loading.attr="disabled"
                                                    wire:target="selectedColumns"
                                                    class="inline-flex items-center px-2 py-1 disabled:opacity-50 disabled:cursor-wait"
                                                >
                                                    <input
                                                        class="text-indigo-600 transition duration-150 ease-in-out border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600 disabled:opacity-50 disabled:cursor-wait"
                                                        wire:model="selectedColumns"
                                                        wire:target="selectedColumns"
                                                        wire:loading.attr="disabled"
                                                        type="checkbox"
                                                        value="{{ $column->getSlug() }}"
                                                    />
                                                    <span class="ml-2">{{ $column->getTitle() }}</span>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($component->paginationIsEnabled() && $component->perPageVisibilityIsEnabled())
                <div>
                    <select
                        wire:model="perPage"
                        id="perPage"
                        class="block w-full px-4 py-2 transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm sm:text-sm sm:leading-5 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    >
                        @foreach ($component->getPerPageAccepted() as $item)
                            <option value="{{ $item }}" wire:key="per-page-{{ $item }}-{{ $component->getTableName() }}">{{ $item === -1 ? __('All') : $item }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-right-end'))
                @include($component->getConfigurableAreaFor('toolbar-right-end'), $component->getParametersForConfigurableArea('toolbar-right-end'))
            @endif
        </div>
    </div>

    @if ($component->filtersAreEnabled() && $component->filtersVisibilityIsEnabled() && $component->hasVisibleFilters() && $component->isFilterLayoutSlideDown())
        <div
            x-cloak
            x-show="filtersOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0"
            x-transition:enter-end="transform opacity-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100"
            x-transition:leave-end="transform opacity-0"
        >
            <div class="grid grid-cols-1 gap-6 px-4 mb-6 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 md:p-0">
                @foreach($component->getFilters() as $filter)
                    @if($filter->isVisibleInMenus())
                        <div class="space-y-1">
                            <label for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
                                class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
                                {{ $filter->getName() }}
                            </label>

                            {{ $filter->render($component) }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@elseif ($theme === 'bootstrap-4')
    <div class="mb-3 d-md-flex justify-content-between">
        <div class="d-md-flex">
            @if ($component->hasConfigurableAreaFor('toolbar-left-start'))
                @include($component->getConfigurableAreaFor('toolbar-left-start'), $component->getParametersForConfigurableArea('toolbar-left-start'))
            @endif

            @if ($component->reorderIsEnabled())
                <div class="mb-3 mr-0 mr-md-2 mb-md-0">
                    <button
                        wire:click="{{ $component->currentlyReorderingIsEnabled() ? 'disableReordering' : 'enableReordering' }}"
                        type="button"
                        class="btn btn-default d-block w-100 d-md-inline"
                    >
                        @if ($component->currentlyReorderingIsEnabled())
                            @lang('Done Reordering')
                        @else
                            @lang('Reorder')
                        @endif
                    </button>
                </div>
            @endif

            @if ($component->searchIsEnabled() && $component->searchVisibilityIsEnabled())
                <div class="mb-3 mb-md-0 input-group">
                    <input
                        wire:model{{ $component->getSearchOptions() }}="{{ $component->getTableName() }}.search"
                        placeholder="{{ __('Search') }}"
                        type="text"
                        class="form-control"
                    >

                    @if ($component->hasSearch())
                        <div class="input-group-append">
                            <button wire:click.prevent="clearSearch" class="btn btn-outline-secondary" type="button">
                                <svg style="width:.75em;height:.75em" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            @if ($component->filtersAreEnabled() && $component->filtersVisibilityIsEnabled() && $component->hasVisibleFilters())
                <div class="mb-3 ml-0 ml-md-2 mb-md-0">
                    <div
                        @if ($component->isFilterLayoutPopover())
                            x-data="{ open: false }"
                            x-on:keydown.escape.stop="open = false"
                            x-on:mousedown.away="open = false"
                        @endif

                        class="btn-group d-block d-md-inline"
                    >
                        <div>
                            <button
                                type="button"
                                class="btn dropdown-toggle d-block w-100 d-md-inline"

                                @if ($component->isFilterLayoutPopover())
                                    x-on:click="open = !open"
                                    aria-haspopup="true"
                                    x-bind:aria-expanded="open"
                                    aria-expanded="true"
                                @endif

                                @if ($component->isFilterLayoutSlideDown())
                                    x-on:click="filtersOpen = !filtersOpen"
                                @endif
                            >
                                @lang('Filters')

                                @if ($count = $component->getFilterBadgeCount())
                                    <span class="badge badge-info">
                                        {{ $count }}
                                    </span>
                                @endif

                                <span class="caret"></span>
                            </button>
                        </div>

                        @if ($component->isFilterLayoutPopover())
                            <ul
                                x-cloak
                                class="dropdown-menu w-100 mt-md-5"
                                x-bind:class="{'show' : open}"
                                role="menu"
                            >
                                @foreach($component->getFilters() as $filter)
                                    @if($filter->isVisibleInMenus())
                                        <div wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" class="p-2">
                                            <label for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" class="mb-2">
                                                {{ $filter->getName() }}
                                            </label>

                                            {{ $filter->render($component) }}
                                        </div>
                                    @endif
                                @endforeach

                                @if ($component->hasAppliedVisibleFiltersWithValuesThatCanBeCleared())
                                    <div class="dropdown-divider"></div>

                                    <button
                                        wire:click.prevent="setFilterDefaults"
                                        x-on:click="open = false"
                                        class="text-center dropdown-item btn"
                                    >
                                        @lang('Clear')
                                    </button>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-left-end'))
                @include($component->getConfigurableAreaFor('toolbar-left-end'), $component->getParametersForConfigurableArea('toolbar-left-end'))
            @endif
        </div>

        <div class="d-md-flex">
            @if ($component->hasConfigurableAreaFor('toolbar-right-start'))
                @include($component->getConfigurableAreaFor('toolbar-right-start'), $component->getParametersForConfigurableArea('toolbar-right-start'))
            @endif

            @if ($component->showBulkActionsDropdown())
                <div class="mb-3 mb-md-0">
                    <div class="dropdown d-block d-md-inline">
                        <button class="btn dropdown-toggle d-block w-100 d-md-inline" type="button" id="{{ $component->getTableName() }}-bulkActionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('Bulk Actions')
                        </button>

                        <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="{{ $component->getTableName() }}-bulkActionsDropdown">
                            @foreach($component->getBulkActions() as $action => $title)
                                <a
                                    href="#"
                                    wire:click="{{ $action }}"
                                    wire:key="bulk-action-{{ $action }}-{{ $component->getTableName() }}"
                                    class="dropdown-item"
                                >
                                    {{ $title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($component->columnSelectIsEnabled())
                <div class="@if ($component->getColumnSelectIsHiddenOnMobile()) d-none d-sm-block @elseif ($component->getColumnSelectIsHiddenOnTablet()) d-none d-md-block @endif mb-3 mb-md-0 pl-0 pl-md-2">
                    <div
                        x-data="{ open: false }"
                        x-on:keydown.escape.stop="open = false"
                        x-on:mousedown.away="open = false"
                        class="dropdown d-block d-md-inline"
                        wire:key="column-select-button-{{ $component->getTableName() }}"
                    >
                        <button
                            x-on:click="open = !open"
                            class="btn dropdown-toggle d-block w-100 d-md-inline"
                            type="button"
                            id="columnSelect-{{ $component->getTableName() }}"
                            aria-haspopup="true"
                            x-bind:aria-expanded="open"
                        >
                            @lang('Columns')
                        </button>

                        <div
                            class="mt-0 dropdown-menu dropdown-menu-right w-100 mt-md-3"
                            x-bind:class="{'show' : open}"
                            aria-labelledby="columnSelect-{{ $component->getTableName() }}"
                        >
                            <div>
                                <label
                                    wire:loading.attr="disabled"
                                    class="px-2 mb-1"
                                >
                                    <input
                                        @if($component->allDefaultVisibleColumnsAreSelected())
                                            checked
                                            wire:click="deselectAllColumns"
                                        @else
                                            unchecked
                                            wire:click="selectAllColumns"
                                        @endif
                                        wire:loading.attr="disabled"
                                        type="checkbox"
                                    />
                                    <span class="ml-2">{{ __('All Columns') }}</span>
                                </label>
                            </div>
                            @foreach($component->getColumns() as $column)
                                @if ($column->isVisible() && $column->isSelectable())
                                    <div wire:key="columnSelect-{{ $loop->index }}-{{ $component->getTableName() }}">
                                        <label
                                            wire:loading.attr="disabled"
                                            wire:target="selectedColumns"
                                            class="px-2 {{ $loop->last ? 'mb-0' : 'mb-1' }}"
                                        >
                                            <input
                                                wire:model="selectedColumns"
                                                wire:target="selectedColumns"
                                                wire:loading.attr="disabled"
                                                type="checkbox"
                                                value="{{ $column->getSlug() }}"
                                            />
                                            <span class="ml-2">{{ $column->getTitle() }}</span>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($component->paginationIsEnabled() && $component->perPageVisibilityIsEnabled())
                <div class="ml-0 ml-md-2">
                    <select
                        wire:model="perPage"
                        id="perPage"
                        class="form-control"
                    >
                        @foreach ($component->getPerPageAccepted() as $item)
                            <option value="{{ $item }}" wire:key="per-page-{{ $item }}-{{ $component->getTableName() }}">{{ $item === -1 ? __('All') : $item }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-right-end'))
                @include($component->getConfigurableAreaFor('toolbar-right-end'), $component->getParametersForConfigurableArea('toolbar-right-end'))
            @endif
        </div>
    </div>

    @if ($component->filtersAreEnabled() && $component->filtersVisibilityIsEnabled() && $component->hasVisibleFilters() && $component->isFilterLayoutSlideDown())
        <div
            x-cloak
            x-show="filtersOpen"
        >
            <div class="container">
                <div class="row">
                    @foreach($component->getFilters() as $filter)
                        @if($filter->isVisibleInMenus())
                            <div class="mb-4 col-12 col-sm-6 col-md-4 col-lg-3">
                                <label for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
                                    class="d-block">
                                    {{ $filter->getName() }}
                                </label>

                                {{ $filter->render($component) }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@elseif ($theme === 'bootstrap-5')
    <div class="mb-3 d-md-flex justify-content-between">
        <div class="d-md-flex">
            @if ($component->hasConfigurableAreaFor('toolbar-left-start'))
                @include($component->getConfigurableAreaFor('toolbar-left-start'), $component->getParametersForConfigurableArea('toolbar-left-start'))
            @endif

            @if ($component->reorderIsEnabled())
                <div class="mb-3 me-0 me-md-2 mb-md-0">
                    <button
                        wire:click="{{ $component->currentlyReorderingIsEnabled() ? 'disableReordering' : 'enableReordering' }}"
                        type="button"
                        class="btn btn-default d-block w-100 d-md-inline"
                    >
                        @if ($component->currentlyReorderingIsEnabled())
                            @lang('Done Reordering')
                        @else
                            @lang('Reorder')
                        @endif
                    </button>
                </div>
            @endif

            @if ($component->searchIsEnabled() && $component->searchVisibilityIsEnabled())
                <div class="mb-3 mb-md-0 input-group">
                    <input
                        wire:model{{ $component->getSearchOptions() }}="{{ $component->getTableName() }}.search"
                        placeholder="{{ __('Search') }}"
                        type="text"
                        class="form-control"
                    >

                    @if ($component->hasSearch())
                        <button wire:click.prevent="clearSearch"  class="btn btn-outline-secondary" type="button">
                            <svg style="width:.75em;height:.75em" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            @if ($component->filtersAreEnabled() && $component->filtersVisibilityIsEnabled() && $component->hasVisibleFilters())
                <div class="{{ $component->searchIsEnabled() ? 'ms-0 ms-md-2' : '' }} mb-3 mb-md-0">
                    <div
                        @if ($component->isFilterLayoutPopover())
                            x-data="{ open: false }"
                            x-on:keydown.escape.stop="open = false"
                            x-on:mousedown.away="open = false"
                        @endif

                        class="btn-group d-block d-md-inline"
                    >
                        <div>
                            <button
                                type="button"
                                class="btn dropdown-toggle d-block w-100 d-md-inline"

                                @if ($component->isFilterLayoutPopover())
                                    x-on:click="open = !open"
                                    aria-haspopup="true"
                                    x-bind:aria-expanded="open"
                                    aria-expanded="true"
                                @endif

                                @if ($component->isFilterLayoutSlideDown())
                                    x-on:click="filtersOpen = !filtersOpen"
                                @endif
                            >
                                @lang('Filters')

                                @if ($count = $component->getFilterBadgeCount())
                                    <span class="badge bg-info">
                                        {{ $count }}
                                    </span>
                                @endif

                                <span class="caret"></span>
                            </button>
                        </div>

                        @if ($component->isFilterLayoutPopover())
                            <ul
                                x-cloak
                                class="dropdown-menu w-100"
                                x-bind:class="{'show' : open}"
                                role="menu"
                            >
                                @foreach($component->getFilters() as $filter)
                                    @if($filter->isVisibleInMenus())
                                        <div wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" class="p-2">
                                            <label for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" class="mb-2">
                                                {{ $filter->getName() }}
                                            </label>

                                            {{ $filter->render($component) }}
                                        </div>
                                    @endif
                                @endforeach

                                @if ($component->hasAppliedVisibleFiltersWithValuesThatCanBeCleared())
                                    <div class="dropdown-divider"></div>

                                    <button
                                        wire:click.prevent="setFilterDefaults"
                                        x-on:click="open = false"
                                        class="text-center dropdown-item"
                                    >
                                        @lang('Clear')
                                    </button>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-left-end'))
                @include($component->getConfigurableAreaFor('toolbar-left-end'), $component->getParametersForConfigurableArea('toolbar-left-end'))
            @endif
        </div>

        <div class="d-md-flex">
            @if ($component->hasConfigurableAreaFor('toolbar-right-start'))
                @include($component->getConfigurableAreaFor('toolbar-right-start'), $component->getParametersForConfigurableArea('toolbar-right-start'))
            @endif

            @if ($component->showBulkActionsDropdown())
                <div class="mb-3 mb-md-0">
                    <div class="dropdown d-block d-md-inline">
                        <button class="btn dropdown-toggle d-block w-100 d-md-inline" type="button" id="{{ $component->getTableName() }}-bulkActionsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('Bulk Actions')
                        </button>

                        <div class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="{{ $component->getTableName() }}-bulkActionsDropdown">
                            @foreach($component->getBulkActions() as $action => $title)
                                <a
                                    href="#"
                                    wire:click.prevent="{{ $action }}"
                                    wire:key="bulk-action-{{ $action }}-{{ $component->getTableName() }}"
                                    class="dropdown-item"
                                >
                                    {{ $title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($component->columnSelectIsEnabled())
                <div class="@if ($component->getColumnSelectIsHiddenOnMobile()) d-none d-sm-block @elseif ($component->getColumnSelectIsHiddenOnTablet()) d-none d-md-block @endif mb-3 mb-md-0 md-0 ms-md-2">
                    <div
                        x-data="{ open: false }"
                        x-on:keydown.escape.stop="open = false"
                        x-on:mousedown.away="open = false"
                        class="dropdown d-block d-md-inline"
                        wire:key="column-select-button-{{ $component->getTableName() }}"
                    >
                        <button
                            x-on:click="open = !open"
                            class="btn dropdown-toggle d-block w-100 d-md-inline"
                            type="button"
                            id="columnSelect-{{ $component->getTableName() }}"
                            aria-haspopup="true"
                            x-bind:aria-expanded="open"
                        >
                            @lang('Columns')
                        </button>

                        <div
                            class="dropdown-menu dropdown-menu-end w-100"
                            x-bind:class="{'show' : open}"
                            aria-labelledby="columnSelect-{{ $component->getTableName() }}"
                        >
                            <div class="form-check ms-2">
                                <input
                                    @if($component->allDefaultVisibleColumnsAreSelected())
                                        checked
                                    wire:click="deselectAllColumns"
                                    @else
                                        unchecked
                                    wire:click="selectAllColumns"
                                    @endif
                                    wire:loading.attr="disabled"
                                    type="checkbox"
                                    class="form-check-input"
                                />
                                <label
                                    wire:loading.attr="disabled"
                                    class="form-check-label"
                                >
                                    {{ __('All Columns') }}
                                </label>
                            </div>
                            @foreach($component->getColumns() as $column)
                                @if ($column->isVisible() && $column->isSelectable())
                                    <div wire:key="columnSelect-{{ $loop->index }}-{{ $component->getTableName() }}"
                                         class="form-check ms-2"
                                    >
                                        <input
                                            wire:model="selectedColumns"
                                            wire:target="selectedColumns"
                                            wire:loading.attr="disabled"
                                            type="checkbox"
                                            class="form-check-input"
                                            value="{{ $column->getSlug() }}"
                                        />
                                        <label
                                            wire:loading.attr="disabled"
                                            wire:target="selectedColumns"
                                            class="{{ $loop->last ? 'mb-0' : 'mb-1' }} form-check-label"
                                        >{{ $column->getTitle() }}</label>

                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($component->paginationIsEnabled() && $component->perPageVisibilityIsEnabled())
                <div class="ms-0 ms-md-2">
                    <select
                        wire:model="perPage"
                        id="perPage"
                        class="form-select"
                    >
                        @foreach ($component->getPerPageAccepted() as $item)
                            <option value="{{ $item }}" wire:key="per-page-{{ $item }}-{{ $component->getTableName() }}">{{ $item === -1 ? __('All') : $item }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($component->hasConfigurableAreaFor('toolbar-right-end'))
                @include($component->getConfigurableAreaFor('toolbar-right-end'), $component->getParametersForConfigurableArea('toolbar-right-end'))
            @endif
        </div>
    </div>

    @if ($component->filtersAreEnabled() && $component->filtersVisibilityIsEnabled() && $component->hasVisibleFilters() && $component->isFilterLayoutSlideDown())
        <div
            x-cloak
            x-show="filtersOpen"
        >
            <div class="container">
                <div class="row">
                    @foreach($component->getFilters() as $filter)
                        @if($filter->isVisibleInMenus())
                            <div class="mb-4 col-12 col-sm-6 col-md-4 col-lg-3">
                                <label for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
                                    class="d-block">
                                    {{ $filter->getName() }}
                                </label>

                                {{ $filter->render($component) }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endif

@if ($component->hasConfigurableAreaFor('after-toolbar'))
    @include($component->getConfigurableAreaFor('after-toolbar'), $component->getParametersForConfigurableArea('after-toolbar'))
@endif
