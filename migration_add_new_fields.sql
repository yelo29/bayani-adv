-- Migration script to add new fields to existing products table
-- Run this on your existing database to add the new columns

USE gawang_pinas;

-- Add heritage_story column if it doesn't exist
ALTER TABLE products ADD COLUMN IF NOT EXISTS heritage_story TEXT AFTER description;

-- Add where_to_find column if it doesn't exist
ALTER TABLE products ADD COLUMN IF NOT EXISTS where_to_find TEXT AFTER heritage_story;

-- Add did_you_know column if it doesn't exist
ALTER TABLE products ADD COLUMN IF NOT EXISTS did_you_know TEXT AFTER where_to_find;
