<?php
require '../php/auth.php';
require_login();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Profile page</title>
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-primary alert-dismissible position-absolute top-0 end-0" style="z-index: 9999;" role="alert">
        ' . $_SESSION['message'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        unset($_SESSION['message']);
    }

    include 'components/header.php'
    ?>

    <div class="container my-5">
        <div class="">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2>Sections</h2>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal">
                    Add new section
                </button>
            </div>

            <div class="modal fade" id="sectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add new section</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/php/section.php?addSection=true" method="post">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Parent section:</label>
                                    <select name="parent_id" class="form-select">
                                        <option value="">No parent section</option>
                                        <?php
                                        require '../php/db.php';
                                        $stmt = $db->query('SELECT * FROM sections');
                                        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($sections as $section) {
                                            echo '<option value="' . $section['id'] . '">' . $section['title'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Title:</label>
                                    <input type="text" name="title" required class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description:</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
            require '../php/db.php';

            // Function to gather all descendant IDs
            function getDescendantIds($parentId, $sections)
            {
                $descendantIds = [];
                foreach ($sections as $section) {
                    if ($section['parent_id'] == $parentId) {
                        $descendantIds[] = $section['id'];
                        $descendantIds = array_merge($descendantIds, getDescendantIds($section['id'], $sections));
                    }
                }
                return $descendantIds;
            }

            $stmt = $db->query('SELECT s1.*, s2.title as parent_title FROM sections s1 LEFT JOIN sections s2 ON s1.parent_id = s2.id');
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            ?>

            <table class="table">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th width="220">Title</th>
                        <th width="400">Description</th>
                        <th width="220">Parent section</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($sections as $section) {
                        $description = mb_strlen($section['description']) > 80 ? mb_substr($section['description'], 0, 80) . '...' : $section['description'];
                        echo '<tr>
                            <td>' . $section['id'] . '</td>
                            <td>' . $section['title'] . '</td>
                            <td>' . $description . '</td>
                            <td>' . (isset($section['parent_title']) ? $section['parent_title'] : 'None') . '</td>
                            <td class="text-end">
                                <div>
                                    <a href="/php/section.php?deleteSection=' . $section['id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this? Child sections will be deleted too!\')">Delete</a>
                                    <button type="button" class="btn btn-warning text-black" data-bs-toggle="modal" data-bs-target="#updateModal' . $section['id'] . '">Update</button>
                                </div>
                            </td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <?php
            foreach ($sections as $section) {
                // Modal for update section
                echo '<div class="modal fade" id="updateModal' . $section['id'] . '" tabindex="-1" aria-labelledby="updateModalLabel' . $section['id'] . '" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalTitle' . $section['id'] . '">Update section</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="/php/section.php?updateSection=true" method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="' . $section['id'] . '">
                                    <div class="mb-3">
                                        <label class="form-label">Parent section:</label>
                                        <select name="parent_id" class="form-select">
                                            <option value="">No parent section</option>';

                $parentId = $section['parent_id'] ? $section['parent_id'] : null;
                $editingSectionId = $section['id'];

                // Get all sections for the dropdown options
                $stmt = $db->query('SELECT * FROM sections');
                $allSections = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get all descendant IDs to exclude
                $descendantIds = getDescendantIds($editingSectionId, $allSections);

                foreach ($allSections as $option) {
                    // Skip the current section and its descendants
                    if ($option['id'] === $editingSectionId || in_array($option['id'], $descendantIds)) {
                        continue;
                    };
                    $selected = $parentId == $option['id'] ? 'selected' : '';
                    echo '<option value="' . $option['id'] . '"' . $selected . '>' . $option['title'] . '</option>';
                }

                echo '</select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Title:</label>
                                        <input type="text" name="title" required class="form-control" value="' . $section['title'] . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="description">Description:</label>
                                        <textarea name="description" id="description" class="form-control" rows="4">' . $section['description'] . '</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <?php
    include 'components/footer.php'
    ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>