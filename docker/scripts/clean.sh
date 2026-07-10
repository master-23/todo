#!/bin/bash

docker compose down -v

rm -rf logs/*
rm -rf coverage/*

echo "Cleanup completed!"