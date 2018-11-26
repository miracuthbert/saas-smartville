<?php

namespace Smartville\App\Filters\Status;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class InvoiceStatusFilter extends FilterAbstract
{
    /**
     * Database column mappings.
     *
     * @return array
     */
    public function columnMappings()
    {
        return [
            'draft' => 'sent_at',
            'past_due' => 'due_at',
            'queued' => 'sent_at',
            'sent' => 'sent_at',
            'cleared' => 'cleared_at',
        ];
    }

    /**
     * Database where column mappings.
     *
     * @return array
     */
    public function whereMappings()
    {
        return [
            'draft' => 'whereNull',
            'queued' => 'whereDate',
            'sent' => 'whereDate',
            'past_due' => 'whereDate',
            'cleared' => 'whereNotNull',
        ];
    }

    /**
     * Database operator mappings.
     *
     * @return array
     */
    protected function operators()
    {
        return [
            'draft' => '=',
            'queued' => '>',
            'sent' => '<=',
            'past_due' => '<',
        ];
    }

    /**
     * Apply invoice status filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        if ($value == null) {
            return $builder;
        }

        $builder = $this->resolveFilterQueryFromValue($builder, $value);

        return $builder;
    }

    /**
     * Resolve the filter query by given value.
     *
     * @param Builder $builder
     * @param $value
     * @return $this|Builder
     */
    private function resolveFilterQueryFromValue(Builder $builder, $value)
    {
        $where = $this->resolveWhereClause($value);
        $operator = $this->resolveFilterOperator($value);
        $resolvedColumn = $this->resolveFilterColumn($value);

        if ($value == "past_due") {
            $builder = $builder->whereNull('cleared_at');
        }

        if ($where == "whereNull") {
            return $builder->{$where}($resolvedColumn);
        }

        if ($where == "whereNotNull") {
            return $builder->{$where}($resolvedColumn);
        }

        return $builder->{$where}(
            $resolvedColumn,
            $operator,
            Carbon::now()->toDateString()
        );
    }
}
