/**
 * @license
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
import { Query } from '../core/query';
import { DocumentKeySet, DocumentMap, DocumentSizeEntries, DocumentSizeEntry, MaybeDocumentMap, NullableMaybeDocumentMap } from '../model/collections';
import { MaybeDocument } from '../model/document';
import { DocumentKey } from '../model/document_key';
import { FirestoreError } from '../util/error';
import { IndexManager } from './index_manager';
import { DbRemoteDocument } from './indexeddb_schema';
import { LocalSerializer } from './local_serializer';
import { PersistenceTransaction } from './persistence';
import { PersistencePromise } from './persistence_promise';
import { RemoteDocumentCache } from './remote_document_cache';
import { RemoteDocumentChangeBuffer } from './remote_document_change_buffer';
import { SimpleDbTransaction } from './simple_db';
export declare class IndexedDbRemoteDocumentCache implements RemoteDocumentCache {
    readonly serializer: LocalSerializer;
    private readonly indexManager;
    private readonly keepDocumentChangeLog;
    /** The last id read by `getNewDocumentChanges()`. */
    private _lastProcessedDocumentChangeId;
    /**
     * @param {LocalSerializer} serializer The document serializer.
     * @param {IndexManager} indexManager The query indexes that need to be maintained.
     * @param keepDocumentChangeLog Whether to keep a document change log in
     * IndexedDb. This change log is required for Multi-Tab synchronization, but
     * not needed in clients that don't share access to their remote document
     * cache.
     */
    constructor(serializer: LocalSerializer, indexManager: IndexManager, keepDocumentChangeLog: boolean);
    readonly lastProcessedDocumentChangeId: number;
    /**
     * Starts up the remote document cache.
     *
     * Reads the ID of the last  document change from the documentChanges store.
     * Existing changes will not be returned as part of
     * `getNewDocumentChanges()`.
     */
    start(transaction: SimpleDbTransaction): PersistencePromise<void>;
    /**
     * Adds the supplied entries to the cache. Adds the given size delta to the cached size.
     */
    addEntries(transaction: PersistenceTransaction, entries: Array<{
        key: DocumentKey;
        doc: DbRemoteDocument;
    }>, sizeDelta: number): PersistencePromise<void>;
    /**
     * Removes a document from the cache. Note that this method does *not* do any
     * size accounting. It is the responsibility of the caller to count the bytes removed
     * and issue a final updateSize() call after removing documents.
     *
     * @param documentKey The key of the document to remove
     * @return The size of the document that was removed.
     */
    removeEntry(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<number>;
    getEntry(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<MaybeDocument | null>;
    /**
     * Looks up an entry in the cache.
     *
     * @param documentKey The key of the entry to look up.
     * @return The cached MaybeDocument entry and its size, or null if we have nothing cached.
     */
    getSizedEntry(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<DocumentSizeEntry | null>;
    getEntries(transaction: PersistenceTransaction, documentKeys: DocumentKeySet): PersistencePromise<NullableMaybeDocumentMap>;
    /**
     * Looks up several entries in the cache.
     *
     * @param documentKeys The set of keys entries to look up.
     * @return A map of MaybeDocuments indexed by key (if a document cannot be
     *     found, the key will be mapped to null) and a map of sizes indexed by
     *     key (zero if the key cannot be found).
     */
    getSizedEntries(transaction: PersistenceTransaction, documentKeys: DocumentKeySet): PersistencePromise<DocumentSizeEntries>;
    private forEachDbEntry;
    getDocumentsMatchingQuery(transaction: PersistenceTransaction, query: Query): PersistencePromise<DocumentMap>;
    getNewDocumentChanges(transaction: PersistenceTransaction): PersistencePromise<MaybeDocumentMap>;
    /**
     * Removes all changes in the remote document changelog through `changeId`
     * (inclusive).
     */
    removeDocumentChangesThroughChangeId(transaction: PersistenceTransaction, changeId: number): PersistencePromise<void>;
    private synchronizeLastDocumentChangeId;
    newChangeBuffer(): RemoteDocumentChangeBuffer;
    getSize(txn: PersistenceTransaction): PersistencePromise<number>;
    private getMetadata;
    private setMetadata;
    /**
     * Adds the given delta to the cached current size. Callers to removeEntry *must* call this
     * afterwards to update the size of the cache.
     *
     * @param sizeDelta
     */
    updateSize(txn: PersistenceTransaction, sizeDelta: number): PersistencePromise<void>;
}
export declare function isDocumentChangeMissingError(err: FirestoreError): boolean;
/**
 * Retrusn an approximate size for the given document.
 */
export declare function dbDocumentSize(doc: DbRemoteDocument): number;
