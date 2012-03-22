<h1>Glömt lösenord</h1>
<form action="{$urlHandler->getUrl(Medlem, URL_NYTTLOSEN)}" method="post">
  <table class="motiomera_form_table">
    <tr>
      <th>E-postadress</td>
      <td><input type="text" name="epost"  value="{$email}" /></td>
    </tr>
    <tr class="mmLastRow">
      <td></td>
      <td><input type="submit" value="Skicka nytt lösenord" /></td>
    </tr>
  </table>
</form>