<?php
namespace App\Views\Admin;


use SFramework\Classes\Pagination;
use SFramework\Views\ViewPagination as ViewMasterPagination;

class ViewPagination extends ViewMasterPagination {

    public function currentRender() {
        if ($this->pagesCount == 1) {
            return;
        }

        $isFirst = (int)$this->currentPage <= 1;

        list($urlPath, $urlParams) = explode('?', $this->currentURL);
        $urlParams = array_diff(explode('&', $urlParams), ['', "{$this->parameterName}={$this->currentPage}"]);

        $firstParams = $urlParams;
        $lastParams = $urlParams;

        $previousUrl = '#';
        $firstUrl = '#';
        $previousParams = $urlParams;
        if (!$isFirst) {
            $firstUrl = "{$urlPath}" . (!empty($firstParams) ? '?' : '') . implode('&', $firstParams);
            $previousParams[] = "{$this->parameterName}=" . ($this->currentPage - 1);
            $previousUrl = "{$urlPath}?" . implode('&', $previousParams);
        }

        $isLast = (int)$this->currentPage >= $this->pagesCount || $this->pagesCount == 1;
        $nextUrl = '#';
        $lastUrl = '#';
        $nextParams = $urlParams;
        if (!$isLast) {
            $nextParams[] = "{$this->parameterName}=" . (is_null($this->currentPage) ? 2 : $this->currentPage + 1);
            $nextUrl = "{$urlPath}?" . implode('&', $nextParams);
            $lastParams[] = "{$this->parameterName}={$this->pagesCount}";
            $lastUrl = "{$urlPath}?" . implode('&', $lastParams);
        }

        ?>
        <nav>
            <ul class="pagination">
                <li<?= ($firstUrl == '#' ? ' class="disabled" ' : '') ?>>
                    <? if ($firstUrl == '#'): ?>
                        <span aria-hidden="true">&laquo; Первая</span>
                    <? else: ?>
                        <a aria-label="Previous" href="<?= $firstUrl ?>">
                            <span aria-hidden="true">&laquo; Первая</span>
                        </a>
                    <? endif; ?>
                </li>
                <li<?= ($previousUrl == '#' ? ' class="disabled" ' : '') ?>>
                    <? if ($previousUrl == '#'): ?>
                        <span aria-hidden="true">&laquo;</span>
                    <? else: ?>
                        <a aria-label="Previous" href="<?= $previousUrl ?>">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    <? endif; ?>
                </li>
                <? for ($i = 1; $i <= $this->pagesCount; $i++): ?>
                    <? $pageParams = $urlParams; ?>
                    <? if ((int)$this->currentPage == $i || (is_null($this->currentPage) && $i == 1)): ?>
                    <li class="active">
                        <span><?= $i ?></span>
                    </li>
                    <? else: ?>
                    <? $pageParams[] = "{$this->parameterName}={$i}"; ?>
                    <li>
                        <a href="<?= "$urlPath?" . implode('&', $pageParams) ?>"><?= $i ?></a>
                    </li>
                    <? endif; ?>
                <? endfor; ?>
                <li<?= ($nextUrl == '#' ? ' class="disabled" ' : '') ?>>
                    <? if ($nextUrl == '#'): ?>
                        <span aria-hidden="true">&raquo;</span>
                    <? else: ?>
                        <a aria-label="Next" href="<?= $nextUrl ?>">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    <? endif; ?>
                </li>
                <li<?= ($lastUrl == '#' ? ' class="disabled" ' : '') ?>>
                    <? if ($lastUrl == '#'): ?>
                        <span aria-hidden="true">&raquo; Последняя</span>
                    <? else: ?>
                        <a aria-label="Next" href="<?= $lastUrl ?>">
                            <span aria-hidden="true">&raquo; Последняя</span>
                        </a>
                    <? endif; ?>
                </li>
            </ul>
        </nav>
        <?
    }

} 