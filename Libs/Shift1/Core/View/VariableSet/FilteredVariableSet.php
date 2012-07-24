<?php
namespace Shift1\Core\View\VariableSet;

class FilteredVariableSet extends AbstractVariableSet {

    const FILTER_STRING_SEPARATOR = ' ';

    protected $currentFilters = array();
    protected $staticFilters = array();

    protected function splitFilterString($filterString) {
        return \explode(self::FILTER_STRING_SEPARATOR, $filterString);
    }

    public function filter($filter) {
        $this->currentFilters = $this->splitFilterString($filter);
        return $this;
    }

    protected function filterVariable($filterServiceName, $var) {
        $filterClass = $this->getContainer()->get('viewFilter.' . $filterServiceName);
        /** @var \Shift1\Core\View\Filter\ViewFilterInterface $filterClass */
        return $filterClass->setVal($var)->getVal();
    }

    public function setDefaultFilter($filter) {
        $this->staticFilters = $this->splitFilterString($filter);
    }

    public function get($key) {
        $var = parent::get($key);
        $processedFilters = array();

        foreach($this->staticFilters as $filter) {
            $var = $this->filterVariable($filter, $var);
            $processedFilters[] = $filter;
        }

        foreach($this->currentFilters as $filter) {
            if(!\in_array($filter, $processedFilters)) {
                // Process filter not twice
                $var = $this->filterVariable($filter, $var);
            }

        }

        $this->currentFilters = array();
        return $var;

    }


}
