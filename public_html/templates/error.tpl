<h1>Ett fel har uppstått</h1>
<p>Var god försök igen senare</p>
{if $error}
<hr />
<strong>Debug message:</strong>
<pre>
{$error|print_r}
</pre>
{/if}