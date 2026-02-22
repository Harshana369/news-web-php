<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;

class QueryBuilder extends BaseBuilder
{
    /**
     * Variable to store USE INDEX statements.
     */
    protected array $useIndexes = [];

    /**
     * Variable to store FORCE INDEX statements.
     */
    protected array $forceIndexes = [];

    /**
     * Method to add a USE INDEX statement.
     *
     * @param string $index The index name to be used.
     * @return self
     */
    public function addUseIndex(string $index): self
    {
        $this->useIndexes[] = $index;
        return $this;
    }

    /**
     * Method to add a FORCE INDEX statement.
     *
     * @param string $index The index name to be forced.
     * @return self
     */
    public function addForceIndex(string $index): self
    {
        $this->forceIndexes[] = $index;
        return $this;
    }

    /**
     * Builds the FROM part of the query, including USE INDEX or FORCE INDEX if applicable.
     *
     * @return string
     */
    protected function _fromTables(): string
    {
        $table = parent::_fromTables();

        if (FORCE_DB_INDEXES) {
            if (!empty($this->forceIndexes)) {
                $table .= ' FORCE INDEX (' . implode(', ', $this->forceIndexes) . ')';
            } elseif (!empty($this->useIndexes)) {
                $table .= ' USE INDEX (' . implode(', ', $this->useIndexes) . ')';
            }
        }

        // Clear indexes after query execution
        $this->useIndexes = [];
        $this->forceIndexes = [];

        return $table;
    }
}