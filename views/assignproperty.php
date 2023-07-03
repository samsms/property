<?php
include '../modules/functions.php';
$conn = getMysqliConnection();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Agent Property Assignment</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">
  <style>
    /* Add your custom CSS styles here */
    body {
      font-family: Arial, sans-serif;
    }

    h2, h3 {
      margin-bottom: 10px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    select {
      margin-bottom: 10px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .unassign-button {
      background-color: #f44336;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#agent-properties').DataTable({
        paging: true
      });

      // Load properties based on selected agent
      $('#agent').change(function() {
        var selectedAgent = $(this).val();
        $.ajax({
          url: 'update_agent.php',
          type: 'POST',
          data: { agent: selectedAgent },
          success: function(response) {
            window.location.reload();
          },
          error: function(xhr, status, error) {
            console.error('Error updating selected agent: ' + error);
          }
        });
      });

      // CSV import functionality
      $('#csv-form').submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        $.ajax({
          url: 'import_csv.php',
          type: 'POST',
          data: formData,
          dataType: 'json',
          contentType: false,
          processData: false,
          success: function(response) {
            if (response.success) {
              // Reload the page to display the updated agent properties
              window.location.reload();
            } else {
              console.error('CSV import failed: ' + response.error);
            }
          },
          error: function(xhr, status, error) {
            console.error('CSV import error: ' + error);
          }
        });
      });
    });
  </script>
</head>
<body>
    <a href="../home.php"><----Back</a>
  <div class="container">
    <h2>Agent Property Assignment</h2>

    <?php
      // PHP code to handle form submission and retrieve data from the database
      // Replace the database connection details with your own

      // Start the session
      session_start();

      // Connect to the database
      //$conn = new mysqli("localhost", "username", "password", "database");

      // Check the connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Function to escape special characters in a string for use in an SQL statement
      function escape($value) {
        global $conn;
        return $conn->real_escape_string($value);
      }

      // Handle form submission to assign properties to agent
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['properties'])&& isset($_POST['unassign'])) {
        $properties = $_POST['properties'];
      
        if (!empty($properties)) {
          $propertyIds = array_map('escape', $properties);
          $propertyIdsString = implode(",", $propertyIds);
      
          // Delete the agent property records from the agentproperty table
          $unassignQuery = "DELETE FROM agentproperty WHERE id IN ($propertyIdsString)";
          if ($conn->query($unassignQuery) === TRUE) {
            echo "<p class='alert alert-success'>Properties unassigned successfully.</p>";
          } else {
            echo "<p class='alert alert-danger'>Error unassigning properties: " . $conn->error . "</p>";
          }
        } else {
          echo "<p class='alert alert-danger'>No properties selected.</p>";
        }
      }else
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $agentId = escape($_POST['agent']);
        $properties = $_POST['properties'];
        $commission=10;
        if (!empty($properties)) {
          // Prepare the multi-row insert statement
          $insertQuery = "INSERT INTO agentproperty (agent_id, property_id,propertyname,commission) VALUES ";
          $valueStrings = array();
          
          foreach ($properties as $property) {
            
            $property=json_decode($property);
            $propid=escape($property->id);
            $name=escape($property->name);
            // Check if the property is already assigned to the agent
            $checkQuery = "SELECT * FROM agentproperty WHERE agent_id = '$agentId' AND property_id = '$propid'";
            $checkResult = $conn->query($checkQuery);
    
            if ($checkResult->num_rows == 0) {
              $valueStrings[] = "('$agentId', '$property->id','$name',$commission)";
            }
          }
    
          if (!empty($valueStrings)) {
            $insertQuery .= implode(", ", $valueStrings);
    
            // Insert the assigned properties into the agentproperty table
            if ($conn->query($insertQuery) === TRUE) {
              echo "<p class='alert alert-success'>Properties assigned successfully.</p>";
            } else {
              echo "<p class='alert alert-danger'>Error assigning properties: " . $conn->error . "</p>";
            }
          } else {
            echo "<p class='alert alert-danger'>The selected properties are already assigned to the agent.</p>";
          }
        } else {
          echo "<p class='alert alert-danger'>No properties selected.</p>";
        }
      }
    
      // Handle unassigning a property from an agent
    // Handle unassigning a property from an agent
if (isset($_GET['unassign'])) {
    $unassignIds = explode(',', escape($_GET['unassign']));
    $unassignIds = array_map('trim', $unassignIds);
    $unassignIds = array_filter($unassignIds);
  
    if (!empty($unassignIds)) {
      $unassignIds = implode(',', $unassignIds);
  
      // Delete the agent property records from the agentproperty table
      $unassignQuery = "DELETE FROM agentproperty WHERE id IN ($unassignIds)";
      if ($conn->query($unassignQuery) === TRUE) {
        echo "<p class='alert alert-success'>Properties unassigned successfully.</p>";
      } else {
        echo "<p class='alert alert-danger'>Error unassigning properties: " . $conn->error . "</p>";
      }
    } else {
      echo "<p class='alert alert-danger'>No properties selected to unassign.</p>";
    }
  }
  

      // Retrieve agents from the database
      $agentsQuery = "SELECT * FROM agents";
      $agentsResult = $conn->query($agentsQuery);

      // Retrieve properties from the database
      $propertiesQuery = "SELECT * FROM properties";
      $propertiesResult = $conn->query($propertiesQuery);

      // Retrieve agent properties from the database based on session value
      $selectedAgent = $_SESSION['selectedAgent']; //?? null;
      if ($selectedAgent) {
        $agentPropertiesQuery = "SELECT ap.*, a.agentname, p.property_name
                                 FROM agentproperty ap
                                 INNER JOIN agents a ON ap.agent_id = a.agentid
                                 INNER JOIN properties p ON ap.property_id = p.propertyid
                                 WHERE ap.agent_id = '$selectedAgent'";
      } else {
        // If no agent is selected, assign properties to a randomly selected agent
        $randomAgentQuery = "SELECT agentid FROM agents ORDER BY RAND() LIMIT 1";
        $randomAgentResult = $conn->query($randomAgentQuery);
        $randomAgent = $randomAgentResult->fetch_assoc();
        $randomAgentId = $randomAgent['agentid'] ;//?? null;

        $_SESSION['selectedAgent'] = $randomAgentId;

        $agentPropertiesQuery = "SELECT ap.*, a.agentname, p.property_name
                                 FROM agentproperty ap
                                 INNER JOIN agents a ON ap.agent_id = a.agentid
                                 INNER JOIN properties p ON ap.property_id = p.propertyid
                                 WHERE ap.agent_id = '$randomAgentId'";
      }
      $agentPropertiesResult = $conn->query($agentPropertiesQuery);
    ?>

    <div style="margin-bottom: 20px;">
      <h3>Assign Properties to Agent</h3>
      <form method="post" action="">
        <div class="form-group">
          <label for="agent">Agent:</label>
          <select name="agent" id="agent" class="form-control">
            <option value="">Select Agent</option>
            <?php
              // Display agent names in a dropdown list
              if ($agentsResult->num_rows > 0) {
                while ($agent = $agentsResult->fetch_assoc()) {
                  $selected = $selectedAgent == $agent['agentid'] ? 'selected' : '';
                  echo "<option value='" . $agent['agentid'] . "' " . $selected . ">" . $agent['agentname'] . "</option>";
                }
              }
            ?>
          </select>
        </div>

        <div class="form-group">
  <label for="properties">Properties:</label>
  <select name="properties[]" id="properties" class="form-control" multiple>
    <?php
      // Display property names in a multi-select dropdown
      if ($propertiesResult->num_rows > 0) {
        while ($property = $propertiesResult->fetch_assoc()) {
          $propertyData = json_encode(['id' => $property['propertyid'], 'name' => $property['property_name']]);
          echo "<option value='" . htmlspecialchars($propertyData, ENT_QUOTES) . "'>" . $property['property_name'] . "</option>";
        }
      }
    ?>
  </select>
</div>



        <button type="submit" class="btn btn-primary">Assign Properties</button>
      </form>
    </div>

    <div>
  <h3>Agent Properties:</h3>
  <form method="post" action="" id="unassign-form" onsubmit="return false">
    <?php
      // Display agent properties
      if ($agentPropertiesResult->num_rows > 0) {
        echo "<table class='table'>
                <tr>
                  <th>
                    <input type='checkbox' id='select-all' onchange='toggleSelectAll()'>
                  </th>
                  <th>Agent</th>
                  <th>Property</th>
                  <th>Action</th>
                </tr>";

        while ($agentProperty = $agentPropertiesResult->fetch_assoc()) {
          echo "<tr>
                  <td>
                    <input type='checkbox' name='properties[]' value='" . $agentProperty['id'] . "'>
                  </td>
                  <td>" . $agentProperty['agentname'] . "</td>
                  <td>" . $agentProperty['property_name'] . "</td>
                  <td>
                    <button class='btn btn-danger' onclick='unassignProperty(" . $agentProperty['id'] . ")'>Unassign</button>
                  </td>
                </tr>";
        }

        echo "</table>";

        // Add a button to unassign selected properties
        echo "<input type='hidden' name='unassign' value='unasign'/>";
        echo "<button type='button' class='btn btn-danger' onclick='unassignSelectedProperties()'>Unassign Selected</button>";
      } else {
        echo "<p>No agent properties found.</p>";
      }
    ?>
  </form>
</div>



    <script>
     // Function to toggle select all checkboxes
function toggleSelectAll() {
  var selectAllCheckbox = document.getElementById("select-all");
  var checkboxes = document.querySelectorAll("input[name='properties[]']");

  for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].checked = selectAllCheckbox.checked;
  }
}

// Function to unassign selected properties
// Function to unassign selected properties
function unassignSelectedProperties() {
  var checkboxes = document.querySelectorAll("input[name='properties[]']:checked");
  var propertyIds = [];

  for (var i = 0; i < checkboxes.length; i++) {
    propertyIds.push(checkboxes[i].value);
  }

  if (propertyIds.length > 0) {
    var confirmation = confirm("Are you sure you want to unassign the selected properties?");

    if (confirmation) {
        document.getElementById("unassign-form").submit();
    }
  } else {
    alert("No properties selected.");
  }
}
function unassignProperty(propertyId) {
        var confirmation = confirm("Are you sure you want to unassign this property?");

        if (confirmation) {
          var url = window.location.href.split('?')[0] + '?unassign=' + propertyId;
          window.location.href = url;
          return false;
        }
      }

    </script>

  </body>
</html>
