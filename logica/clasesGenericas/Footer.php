<?php

class Footer
{
    public static function showFooter()
    {
        $footer = '
        <footer class="as-footer">
		<p>ING. LADY ROSERO</p>
		<p>
			<script>
				date = new Date().getFullYear();
				document.write(date);
			</script>
		</p>
		<p>&copy;</p>
	</footer>';
        return $footer;
    }
}
