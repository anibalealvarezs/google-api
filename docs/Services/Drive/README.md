# Google Drive

## Index

- [Google Drive](#google-drive)
  - [Index](#index)
  - [Request methods](#request-methods)
    - [Files](#files)
      - [deleteFile](#deletefile)
    - [Changes](#changes)
  - [Enums](#enums)

---

## Request methods

The following are non-static methods that can be called from instances (client instances) of the [DriveApi](/src/Services/Drive/DriveApi.php) class.

---

### Files

- ### getFileMetadata: _Array_

  `Gets the file data.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required

        - `fileId`: *Integer*
        ID of the file to be retrieved.

    </details><br>

- ### getFile(): _Boolean_

  `Downloads and stores the file.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `fileId`: _String_  
      ID of the file to be retrieved.
  - `path`: _String_  
    Path for saving the file.

  - Optional

        - `stream`: *Boolean*
        "true" to stream the file, "false" to download it. Default: `false`.

    </details><br>

- ### exportFile(): _Boolean_

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `fileId`: _String_  
      ID of the file to be retrieved.
  - `mimeType`: _String_  
    Mime type of the file to be saved. Ex: `application/vnd.openxmlformats-officedocument.presentationml.presentation` for Power Point files (pptx).

  - `path`: _String_  
    Path for saving the file.

  - Optional

        - `stream`: *Boolean*
        "true" to stream the file, "false" to download it. Default: `false`.

    </details><br>

- ### getFilesMetadata(): _Array_

  `Gets a list of files.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Optional

        - `driveId`: *String*,
        The ID of the drive.
        Defaults to `null`.

        - `pageSize`: *Integer*
        The number of files to be listed per page.
        From "1" to "1000". Defaults to `1000`.

        - `orderBy`: *Array*
        A list of sort keys. Valid keys are "createdTime", "folder", "modifiedByMeTime", "modifiedTime", "name", "name_natural", "quotaBytesUsed", "recency", "sharedWithMeTime", "starred", and "viewedByMeTime". Each key sorts ascending by default, but may be reversed with the "desc" modifier. Example usage: `["folder", "modifiedTime desc", "name"]`.
        Defaults to `[]`.

        - `q`: *String*
        A list of terms to filter the results according to the following specifications: [https://developers.google.com/drive/api/v3/search-files](https://developers.google.com/drive/api/v3/search-files).
        Defaults to empty string.

    </details><br>

- ### deleteFile(): _Boolean_

  `Deletes a file permanently.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required

        - `fileId`: *String*
        ID of the file to be deleted.

    </details><br>

---

### Changes

- ### getStartPageToken(): _Array_

  `Gets the page token for the first page of changes list.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Optional

        - `driveId`: *String*
        ID of the drive.

    </details><br>

- ### getChanges(): _Array_

  `Gets the list of changes.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `pageToken`: _String_  
      The page token of the page to be retrieved.
  - Optional

        - `driveId`: *String*
        The ID of the drive. Default: `null`.

        - `pageSize`: *Integer*
        The number of files to be listed per page.
        From "1" to "1000". Defaults to `1000`.

    </details><br>

---

## Enums
