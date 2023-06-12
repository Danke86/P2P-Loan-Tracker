<!-- FOOTER OF EVERY PAGE (for the sidebar)
TO USE: include footer.php at the bottom of your file-->
   <script type="text/javascript">
   $(document).ready(function(){
     //jquery for toggle sub menus
     $('.sub-btn').click(function(){
       $(this).next('.sub-menu').slideToggle();
       $(this).find('.dropdown').toggleClass('rotate');
     });

     //jquery for expand and collapse the sidebar
     $('.menu-btn').click(function(){
       $('.side-bar').addClass('active');
       $('.menu-btn').css("visibility", "hidden");
     });

     $('.close-btn').click(function(){
       $('.side-bar').removeClass('active');
       $('.menu-btn').css("visibility", "visible");
     });
    });

    // get payer names after selecting a group
    $(document).ready(function(){
      $(".group").change(function(){
        var groupid = $(this).val();
        $.ajax({
          url: "get_payer.php",
          method: "POST",
          data: {groupid: groupid},
          success: function(data) {
            $(".payer").html(data);
          }
        });
      });
    });

    // get payer names after selecting a friend
    $(document).ready(function(){
      $(".friend").change(function(){
        var friendid = $(this).val();
        $.ajax({
          url: "get_payer_friend.php",
          method: "POST",
          data: {friendid: friendid},
          success: function(data) {
            $(".fpayer").html(data);
          }
        });
      });
    });

    // filter out rows where oustanding balance == 0
    $(document).ready(function() {
      $("#obTable tbody tr").each(function() {
        var value = parseInt($(this).find("td:eq(1)").text());
        var value2 = parseInt($(this).find("td:eq(2)").text());
        if (value === 0 && value2 === 0) {
          $(this).hide();
        }
      });
    });

    $(document).ready(function() {
      $("#obTableGroup tbody tr").each(function() {
        var value = parseInt($(this).find("td:eq(1)").text());
        var value2 = parseInt($(this).find("td:eq(2)").text());
        if (value === 0 && value2 === 0) {
          $(this).hide();
        }
      });
    });

    // change value of notfriend_names every input to the friendid as the value in options is the uname
    $('input[name=notfriend_names]').on('input',function() {
      var selectedOption = $('option[value="'+$(this).val()+'"]');

      $("#id_form").on("submit", function(){
        //select the id (the value we will pass, userid) from the value (the uname)
        //change the value of datalist/select to the userid when submitting
        $('input[name=notfriend_names]').val(selectedOption.attr('id')); 
      });
    });

    $(document).ready(function() {
      // Attach event listener to search input field
      $('#groupSearchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase(); // Get the value from the search input

        // Loop through each row in the table body
        $('#groupTable tbody tr').each(function() {
          var matchFound = false;

          // Loop through the first to the sixth column
          $(this).find('td:nth-child(1), td:nth-child(2), td:nth-child(4), td:nth-child(5), td:nth-child(6)').each(function() {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
              matchFound = true;
              return false; // Break out of the loop if match is found in any column
            }
          });

          $(this).toggle(matchFound); // Show/hide row based on matchFound value
        });
      });
    });

    $(document).ready(function() {
      // Attach event listener to search input field
      $('#friendSearchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase(); // Get the value from the search input

        // Loop through each row in the table body
        $('#friendTable tbody tr').each(function() {
          var matchFound = false;

          // Loop through the first to the sixth column
          $(this).find('td:nth-child(1), td:nth-child(2), td:nth-child(4), td:nth-child(5), td:nth-child(6)').each(function() {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
              matchFound = true;
              return false; // Break out of the loop if match is found in any column
            }
          });

          $(this).toggle(matchFound); // Show/hide row based on matchFound value
        });
      });
    });


    $(document).ready(function() {
      // Attach event listener to search input field
      $('#friendlistSearchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase(); // Get the value from the search input

        // Loop through each row in the table body
        $('#friendlistTable tbody tr').each(function() {
          var matchFound = false;

          // Only search the first column
          $(this).find('td:nth-child(1)').each(function() {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
              matchFound = true;
              return false; // Break out of the loop if match is found in any column
            }
          });

          $(this).toggle(matchFound); // Show/hide row based on matchFound value
        });
      });
    });


    $(document).ready(function() {
      // Attach event listener to search input field
      $('#grouplistSearchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase(); // Get the value from the search input

        // Loop through each row in the table body
        $('#grouplistTable tbody tr').each(function() {
          var matchFound = false;

          // Loop through the first to the 3rd column
          $(this).find('td:nth-child(1), td:nth-child(2), td:nth-child(3)').each(function() {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
              matchFound = true;
              return false; // Break out of the loop if match is found in any column
            }
          });

          $(this).toggle(matchFound); // Show/hide row based on matchFound value
        });
      });
    });
   </script>
 </body>
</html>