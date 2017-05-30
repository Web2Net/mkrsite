 <div style="border: 1px solid #000; padding: 5px; background: #eee;">
       Шаблоны<br />
       <table style="border: 1px solid #000; width: 100%;">
          <tr>
             <td style="border: 1px solid #000; width: 150px;"><strong>Дата</strong></td>
             <td style="border: 1px solid #000;"><strong>Тема</strong></td>
             <td style="border: 1px solid #000; width: 100px;">БД</td>
          </tr>
       <?php 
       $query = "SELECT * FROM `message` ORDER BY `id` DESC LIMIT 8";
       mysql_query($query);
       while($row = mysql_fetch_array($db->res))
       {
          echo "<tr>
                  <td style='border: 1px solid #ccc; width: 150px;'><a href='?tepl={$row['id']}'>{$row['date']}</a></td>
                  <td style='border: 1px solid #ccc;'>{$row['subject']}</td>
                  <td style='border: 1px solid #ccc; width: 100px;'>{$row['email_bd']}</td>
               </tr>";
       }
       ?>
       
       </table>
       
    
</div>