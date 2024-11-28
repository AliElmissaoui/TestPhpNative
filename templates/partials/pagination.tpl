{if $total_pages > 1}
<nav>
    <ul class="pagination justify-content-end">
        {for $i=1 to $total_pages}
            <li class="page-item {if $i == $current_page}active{/if}">
                <a href="patients.php?page={$i}&search={$search|default:''}" class="page-link">{$i}</a>
            </li>
        {/for}
    </ul>
</nav>
{/if}
