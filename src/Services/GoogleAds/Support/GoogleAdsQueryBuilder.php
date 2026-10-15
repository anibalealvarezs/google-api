<?php

declare(strict_types=1);

namespace Anibalealvarezs\GoogleApi\Services\GoogleAds\Support;

class GoogleAdsQueryBuilder
{
    protected string $resource = 'customer';
    protected array $fields = [];
    protected array $where = [];
    protected ?string $startDate = null;
    protected ?string $endDate = null;
    protected array $orderBys = [];
    protected ?int $limit = null;

    public static function create(): self
    {
        return new self();
    }

    public function from(string $resource): self
    {
        $this->resource = trim($resource);
        return $this;
    }

    public function select(array|string $fields): self
    {
        if (is_string($fields)) {
            $fields = array_map('trim', explode(',', $fields));
        }

        foreach ($fields as $field) {
            $f = trim((string)$field);
            if ($f !== '' && !in_array($f, $this->fields, true)) {
                $this->fields[] = $f;
            }
        }

        return $this;
    }

    public function where(string $clause): self
    {
        $c = trim($clause);
        if ($c !== '') {
            $this->where[] = $c;
        }
        return $this;
    }

    public function dateRange(string $startDate, string $endDate): self
    {
        $this->startDate = trim($startDate);
        $this->endDate = trim($endDate);
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->orderBys[] = trim($field) . ' ' . strtoupper(trim($direction));
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function build(): string
    {
        $fieldsStr = !empty($this->fields) ? implode(', ', $this->fields) : '*';
        $query = "SELECT {$fieldsStr} FROM {$this->resource}";

        $conditions = $this->where;
        if ($this->startDate && $this->endDate) {
            $conditions[] = "segments.date >= '{$this->startDate}' AND segments.date <= '{$this->endDate}'";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        if (!empty($this->orderBys)) {
            $query .= " ORDER BY " . implode(', ', $this->orderBys);
        }

        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }

        return $query;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
