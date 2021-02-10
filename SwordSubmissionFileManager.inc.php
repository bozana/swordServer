<?php

/**
 * @file SwordSubmissionFileManager.inc.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class SwordSubmissionFileManager
 * @brief Extends SubmissionFileManager to support submission
 * files arriving in the POST body (i.e., not in $_FILES)
 *
 */
import('lib.pkp.classes.submission.SubmissionFile');
import('lib.pkp.classes.file.SubmissionFileManager');

class SwordSubmissionFileManager extends SubmissionFileManager {

	/**
	 * @copydoc FileManager::getUploadedFileType()
	 */
	function getUploadedFileType($fileName) {
		$type = PKPString::mime_content_type($fileName);

		if (!empty($type)) return $type;
		return false;
	}

	/**
	 * @copydoc SubmissionFileManager::_handleUpload()
	 */
	function _handleUpload($fileName, $fileStage, $uploaderUserId,
			$revisedFileId = null, $genreId = null, $assocType = null, $assocId = null) {

		$sourceFile = ini_get('upload_tmp_dir') . '/' . $fileName;
		$submissionFile = $this->_instantiateSubmissionFile($sourceFile, $fileStage, $revisedFileId, $genreId, $assocType, $assocId);
		if (is_null($submissionFile)) return null;
		$fileType = $this->getUploadedFileType($sourceFile);
		assert($fileType !== false);
		$submissionFile->setFileType($fileType);
		$submissionFile->setOriginalFileName($this->truncateFileName($fileName));
		$submissionFile->setUploaderUserId($uploaderUserId);
		$submissionFileDao = DAORegistry::getDAO('SubmissionFileDAO');
		return $submissionFileDao->insertObject($submissionFile, $sourceFile, false);
	}
}
